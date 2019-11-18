<?php
/**
 * Handles shell command execution
 */
namespace App\Traits;


use App\Setting;

trait Bash {

    /**
     * Deletes associated CSV data & files if true
     * @var bool
     */
    private $deleteAssociated = true;

    /**
     * Keys will be replaces with values in result
     * @var array
     */
    private $replaces = [
        'SUCCESS' => '<span class="text-success">SUCCESS</span>',
        'FAILURE' => '<span class="text-danger">FAILURE</span>'
    ];

    public function executeRunner($dryRun = true) {

        // setting
        $s = Setting::first();

        // command
        // dry run
        $cmdDry = "{$s->php_name} {$s->docudex_path}/app/console docudex:bulk-import-documents --path='{$s->files_path}' --config='{$s->config_path}' --dry-run=1";
        // document upload (runner)
        $cmd = "cd {$s->docudex_path};{$s->php_name} app/console docudex:bulk-import-runner --path='{$s->files_path}' --size=1000 --concurrent=2";

        // execute
        if ($dryRun) {
            $log = $this->execute($cmdDry);
        } else {
            $log = $this->execute($cmd);

            // delete associated records
            if ($this->deleteAssociated) {
                $this->deleteAssociated();
            }
        }

        // remove duplicates

        // making all line ending chars same
        $log = str_replace("\r", "\n", $log);

        // make array of string
        $log = explode("\n", $log);

        // removing duplicated entries
        $log = array_unique($log);

        // returning back to string
        $log = implode("", $log);

        // we can do it one line with no comment, super bad idea may be.
        // $log = implode("", array_unique(explode("\n", str_replace("\r", "\n", $log))));

        return $log;
    }

    /**
     * Deletes users csv data and files
     * ** There's much better design & way of doing this. but we don't
     * time for that
     */
    private function deleteAssociated() {

        // *** blah... not using repo even we've it :|

        // delete files
        getAuthUser()->files()->truncate();

        // delete csv data
        getAuthUser()->csv_data()->delete();
    }

    /**
     * Executes shell command and return output upon
     * command completion
     * @param string $cmd   Shell command
     * @return string       Comand output
     */
    private function execute(string $cmd) {
        // execute
        $t = shell_exec($cmd);

        // manipulate & return
        return $this->replace($t);
    }

    /**
     * Replaces text from defined set
     * @param $subject string   Replace subject
     * @param bool $nl2br       return new lines as <br/>
     * @return mixed string     End result
     */
    private function replace(string $subject, $nl2br = true) {

        // Matches that will be replaced
        $matches = array_keys($this->replaces);

        // Matches will be replaced with this
        $replaces = array_values($this->replaces);

        // Replaced result
        $result = str_replace($matches, $replaces, $subject);

        // if new line to HTML br requested
        if ($nl2br) {
            $result = nl2br($result);
        }

        return $result;
    }
}

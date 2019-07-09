<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCsvDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('csv_data', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('document_path')->nullable()->comment('DocumentFileName');
            $table->string('client_id')->nullable()->comment('Client_ID');
            $table->string('ca_or_file_sl')->nullable()->comment('CA No/File SL No');
            $table->string('document_type')->nullable()->comment('Document_Type');
            $table->string('customer')->nullable()->comment('Customer Name');
            $table->string('father')->nullable()->comment('Fathers Name');
            $table->string('mother')->nullable()->comment('Mothers Name');
            $table->string('spouse')->nullable()->comment('Spouse Name');
            $table->string('company')->nullable()->comment('Company Name');
            $table->string('dob')->nullable()->comment('Date of Birth');
            $table->string('passport')->nullable()->comment('Passport No');
            $table->string('mobile')->nullable()->comment('Mobile NO');
            $table->string('tin')->nullable()->comment('TIN');
            $table->string('email')->nullable()->comment('Email Address');
            $table->string('nid')->nullable()->comment('NID');
            $table->string('shelf')->nullable()->comment('Shelf');
            $table->string('rack')->nullable()->comment('Rack');
            $table->string('card_or_product')->nullable()->comment('Product/Card');
            $table->string('card_fs')->nullable()->comment('Card FS');
            $table->string('card_holder')->nullable()->comment('Card Holder Name');
            $table->string('card_type')->nullable()->comment('Card Type');
            $table->string('product_type')->nullable()->comment('Product Type');
            $table->string('pwpd')->nullable()->comment('PWPD No');
            $table->string('le_date')->nullable()->comment('LE Date');
            $table->string('serial_le')->nullable()->comment('Serial LE No');
            $table->string('customer_unicode')->nullable()->comment('Customer Name Unicode');
            $table->string('fathers_unicode')->nullable()->comment('Fathers Name Unicode');
            $table->string('mothers_unicode')->nullable()->comment('Mothers Name Unicode');
            $table->string('nid_dob')->nullable()->comment('NID Date Of Birth');
            $table->string('nid_no')->nullable()->comment('NID No');
            $table->string('tax_payer')->nullable()->comment('TAX Payer Name');
            $table->string('tax_payer_tin')->nullable()->comment('Tax Payer TIN No');
            $table->string('issue_date')->nullable()->comment('Issue Date');
            $table->string('interviewer')->nullable()->comment('Interviewer Name');
            $table->string('applicant')->nullable()->comment('Name of Applicant');
            $table->string('profession')->nullable()->comment('Profession');
            $table->string('education')->nullable()->comment('Education');
            $table->string('sel_or_kyc_date')->nullable()->comment('SEL/KYC Date');
            $table->string('loi')->nullable()->comment('LOI Name');
            $table->string('loi_date')->nullable()->comment('LOI Date');
            $table->string('account_no')->nullable()->comment('Account No');
            $table->string('account_type')->nullable()->comment('Account Type');
            $table->string('ac_status')->nullable()->comment('A/C Status');
            $table->string('print_date')->nullable()->comment('Printing Date');
            $table->string('bank')->nullable()->comment('Bank');
            $table->string('branch')->nullable()->comment('Branch');
            $table->string('digitized_by')->nullable()->comment('Digitized By');
            $table->string('file_type')->nullable()->comment('File Type');
            $table->string('batch')->nullable()->comment('Batch ID');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('csv_data');
    }
}

<?php

use GuardsmanPanda\Larabear\Infrastructure\Database\Service\BearMigrationService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create(table: 'bear_security_incident', callback: static function (Blueprint $table): void {
            $table->id();
            if (DB::getPdo()->getAttribute(PDO::ATTR_DRIVER_NAME) === 'pgsql') {
                $table->text(column: 'security_incident_severity');
                $table->text(column: 'security_incident_namespace')->default('default');
                $table->text(column: 'security_incident_headline');
                $table->text(column: 'security_incident_description');
            } else {
                $table->string(column: 'security_incident_severity');
                $table->string(column: 'security_incident_namespace')->default('default');
                $table->string(column: 'security_incident_headline');
                $table->string(column: 'security_incident_description');
            }
            $table->text(column: 'security_incident_remediation')->nullable();
            $table->text(column: 'security_incident_reference')->nullable();

            BearMigrationService::buildUserReferencingColumn(table: $table, columnName: 'caused_by_user_id');
            $table->ipAddress(column: 'request_ip')->nullable();

            if (DB::getPdo()->getAttribute(PDO::ATTR_DRIVER_NAME) === 'pgsql') {
                $table->text(column: 'request_country_code')->nullable();
                $table->text(column: 'request_http_method');
                $table->text(column: 'request_http_path')->nullable();
                $table->text(column: 'request_http_query')->nullable();
                $table->text(column: 'request_http_hostname')->nullable();
            } else {
                $table->string(column: 'request_country_code')->nullable();
                $table->string(column: 'request_http_method');
                $table->string(column: 'request_http_path')->nullable();
                $table->string(column: 'request_http_query')->nullable();
                $table->string(column: 'request_http_hostname')->nullable();
            }
            $table->timestampTz(column: 'created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    public function down(): void {
        Schema::dropIfExists(table: 'bear_security_incident');
    }
};

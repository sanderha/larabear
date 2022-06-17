<?php

use GuardsmanPanda\Larabear\Infrastructure\Database\Service\BearMigrationService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create(table: 'bear_response_error_log', callback: static function (Blueprint $table) {
            $table->id();
            $table->ipAddress(column: 'request_ip');
            BearMigrationService::buildUserReferencingColumn(table: $table, columnName: 'user_id');
            $table->text(column: 'request_country_code')->nullable();
            $table->text(column: 'request_http_method');
            $table->text(column: 'request_http_path');
            $table->text(column: 'request_http_query')->nullable();
            $table->text(column: 'request_http_hostname');
            $table->integer(column: 'response_status_code');
            $table->text(column: 'response_body');
            $table->timestampTz(column: 'created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    public function down(): void {
        Schema::dropIfExists(table: 'bear_response_error_log');
    }
};

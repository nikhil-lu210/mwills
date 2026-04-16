<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Align enquiry statuses with lead workflow: new, contacted, closed.
     */
    public function up(): void
    {
        DB::table('consultation_messages')->where('status', 'read')->update(['status' => 'contacted']);
        DB::table('consultation_messages')->where('status', 'replied')->update(['status' => 'contacted']);
        DB::table('consultation_messages')->where('status', 'archived')->update(['status' => 'closed']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('consultation_messages')->where('status', 'contacted')->update(['status' => 'read']);
        DB::table('consultation_messages')->where('status', 'closed')->update(['status' => 'archived']);
    }
};

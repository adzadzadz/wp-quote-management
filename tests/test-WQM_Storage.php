<?php
/**
 * PHPUnit test for WQM_Storage
 */

class WQM_Storage_Test extends WP_UnitTestCase {
    public function setUp(): void {
        parent::setUp();
        // Ensure the table exists before each test
        if ( method_exists( 'WQM_Storage', 'maybe_create_table' ) ) {
            WQM_Storage::maybe_create_table();
        }
    }
    
    public function test_insert_and_get_quote_db() {
        update_option('wqm_storage_type', 'db');
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'service_type' => 'Test Service',
            'notes' => 'Test notes',
        ];
        $id = WQM_Storage::insert_quote($data);
        $quotes = WQM_Storage::get_quotes();
        $found = false;
        foreach ($quotes as $quote) {
            if ($quote['id'] == $id) {
                $found = true;
                $this->assertEquals('Test User', $quote['name']);
                $this->assertEquals('test@example.com', $quote['email']);
                $this->assertEquals('Test Service', $quote['service_type']);
                $this->assertEquals('pending', $quote['status']);
            }
        }
        $this->assertTrue($found);
    }

    public function test_insert_and_get_quote_cpt() {
        update_option('wqm_storage_type', 'cpt');
        $data = [
            'name' => 'CPT User',
            'email' => 'cpt@example.com',
            'service_type' => 'CPT Service',
            'notes' => 'CPT notes',
        ];
        $id = WQM_Storage::insert_quote($data);
        $quotes = WQM_Storage::get_quotes();
        $found = false;
        foreach ($quotes as $quote) {
            if ($quote['id'] == $id) {
                $found = true;
                $this->assertEquals('CPT User', $quote['name']);
                $this->assertEquals('cpt@example.com', $quote['email']);
                $this->assertEquals('CPT Service', $quote['service_type']);
                $this->assertEquals('pending', $quote['status']);
            }
        }
        $this->assertTrue($found);
    }
}

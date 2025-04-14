<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            ['room_code' => 'R-002', 'room_name' => 'Ruang Meeting 2', 'room_description' => 'Ruang meeting kecil', 'status' => 'Active', 'organization_id' => 1],
            ['room_code' => 'R-003', 'room_name' => 'Ruang Presentasi', 'room_description' => 'Ruang untuk presentasi', 'status' => 'Active', 'organization_id' => 1],
            ['room_code' => 'R-004', 'room_name' => 'Ruang Konferensi', 'room_description' => 'Ruang untuk konferensi', 'status' => 'Active', 'organization_id' => 1],
            ['room_code' => 'R-005', 'room_name' => 'Ruang Diskusi', 'room_description' => 'Ruang diskusi tim', 'status' => 'Inactive', 'organization_id' => 1],
            ['room_code' => 'R-006', 'room_name' => 'Ruang Rapat Kecil', 'room_description' => 'Ruang untuk rapat kecil', 'status' => 'Active', 'organization_id' => 1],
            ['room_code' => 'R-007', 'room_name' => 'Ruang Workshop', 'room_description' => 'Ruang untuk pelatihan', 'status' => 'Inactive', 'organization_id' => 1],
            ['room_code' => 'R-008', 'room_name' => 'Ruang Kerja Bersama', 'room_description' => 'Ruang coworking', 'status' => 'Active', 'organization_id' => 1],
            ['room_code' => 'R-009', 'room_name' => 'Ruang Interview', 'room_description' => 'Ruang untuk wawancara', 'status' => 'Active', 'organization_id' => 1],
            ['room_code' => 'R-010', 'room_name' => 'Ruang Private', 'room_description' => 'Ruang kerja pribadi', 'status' => 'Inactive', 'organization_id' => 1],
            ['room_code' => 'R-011', 'room_name' => 'Ruang Meeting 1', 'room_description' => 'Ruang meeting utama', 'status' => 'Active', 'organization_id' => 1],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}

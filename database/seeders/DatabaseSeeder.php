<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskActivity;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $usersData = [
            [
                'name' => 'Admin BPKH',
                'email' => 'admin@bpkh.go.id',
                'role' => User::ROLE_ADMIN,
            ],
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad.fauzi@bpkh.go.id',
                'role' => User::ROLE_USER,
            ],
            [
                'name' => 'Sri Ramadhani',
                'email' => 'sri.ramadhani@bpkh.go.id',
                'role' => User::ROLE_USER,
            ],
            [
                'name' => 'Dita Kusuma',
                'email' => 'dita.kusuma@bpkh.go.id',
                'role' => User::ROLE_USER,
            ],
            [
                'name' => 'Rafi Pratama',
                'email' => 'rafi.pratama@bpkh.go.id',
                'role' => User::ROLE_USER,
            ],
        ];

        $users = collect($usersData)->mapWithKeys(function (array $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make('password123'),
                    'role' => $userData['role'],
                ]
            );

            return [$user->email => $user];
        });

        $projectsData = [
            [
                'name' => 'Integrasi Dashboard Setoran Haji',
                'description' => 'Monitoring setoran haji dari bank mitra dan ringkasan dana masuk harian.',
                'owner' => 'sri.ramadhani@bpkh.go.id',
                'members' => [
                    'dita.kusuma@bpkh.go.id',
                    'ahmad.fauzi@bpkh.go.id',
                ],
                'tasks' => [
                    [
                        'title' => 'Sinkronisasi data setoran bulanan',
                        'description' => 'Ambil data setoran dari bank mitra dan normalisasi format kolom.',
                        'status' => 'in_progress',
                        'priority' => 'high',
                        'due_date' => Carbon::now()->addDays(7),
                        'created_by' => 'sri.ramadhani@bpkh.go.id',
                        'assignees' => [
                            'ahmad.fauzi@bpkh.go.id',
                            'dita.kusuma@bpkh.go.id',
                        ],
                        'activities' => [
                            [
                                'user' => 'ahmad.fauzi@bpkh.go.id',
                                'comment' => 'Mapping format CSV dari bank mitra sudah selesai.',
                            ],
                            [
                                'user' => 'sri.ramadhani@bpkh.go.id',
                                'comment' => 'Tinggal finalisasi validasi jumlah setoran.',
                            ],
                            [
                                'user' => 'dita.kusuma@bpkh.go.id',
                                'comment' => 'Sample data Maret sudah diunggah ke staging.',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Validasi rekonsiliasi bank mitra',
                        'description' => 'Bandingkan total setoran dengan data mutasi bank untuk tanggal berjalan.',
                        'status' => 'todo',
                        'priority' => 'medium',
                        'due_date' => Carbon::now()->addDays(10),
                        'created_by' => 'dita.kusuma@bpkh.go.id',
                        'assignees' => [
                            'dita.kusuma@bpkh.go.id',
                        ],
                        'activities' => [
                            [
                                'user' => 'dita.kusuma@bpkh.go.id',
                                'comment' => 'Menunggu sample data rekonsiliasi dari bank mitra.',
                            ],
                            [
                                'user' => 'ahmad.fauzi@bpkh.go.id',
                                'comment' => 'Sudah koordinasi dengan tim bank mitra untuk akses data.',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Tampilan ringkasan dana masuk',
                        'description' => 'Buat widget ringkas dana masuk harian di dashboard.',
                        'status' => 'done',
                        'priority' => 'low',
                        'due_date' => Carbon::now()->subDays(2),
                        'created_by' => 'ahmad.fauzi@bpkh.go.id',
                        'assignees' => [
                            'ahmad.fauzi@bpkh.go.id',
                        ],
                        'activities' => [
                            [
                                'user' => 'ahmad.fauzi@bpkh.go.id',
                                'comment' => 'Widget ringkasan sudah tampil di dashboard QA.',
                            ],
                            [
                                'user' => 'sri.ramadhani@bpkh.go.id',
                                'comment' => 'Mohon cek ulang warna indikator sebelum dipublish.',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Notifikasi keterlambatan setoran',
                        'description' => 'Trigger notifikasi bila setoran harian belum masuk sampai jam 16.00.',
                        'status' => 'todo',
                        'priority' => 'high',
                        'due_date' => Carbon::now()->addDays(14),
                        'created_by' => 'sri.ramadhani@bpkh.go.id',
                        'assignees' => [
                            'sri.ramadhani@bpkh.go.id',
                            'ahmad.fauzi@bpkh.go.id',
                        ],
                        'activities' => [
                            [
                                'user' => 'sri.ramadhani@bpkh.go.id',
                                'comment' => 'Perlu konfirmasi aturan SLA dari unit keuangan.',
                            ],
                            [
                                'user' => 'ahmad.fauzi@bpkh.go.id',
                                'comment' => 'Draft aturan SLA sudah dirangkum, siap review.',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Monitoring Likuiditas Dana Haji',
                'description' => 'Pantau proyeksi likuiditas untuk kebutuhan operasional haji.',
                'owner' => 'ahmad.fauzi@bpkh.go.id',
                'members' => [
                    'rafi.pratama@bpkh.go.id',
                    'dita.kusuma@bpkh.go.id',
                ],
                'tasks' => [
                    [
                        'title' => 'Dashboard proyeksi likuiditas',
                        'description' => 'Bangun tampilan proyeksi arus kas 3 bulan ke depan.',
                        'status' => 'in_progress',
                        'priority' => 'high',
                        'due_date' => Carbon::now()->addDays(12),
                        'created_by' => 'ahmad.fauzi@bpkh.go.id',
                        'assignees' => [
                            'ahmad.fauzi@bpkh.go.id',
                            'rafi.pratama@bpkh.go.id',
                        ],
                        'activities' => [
                            [
                                'user' => 'rafi.pratama@bpkh.go.id',
                                'comment' => 'Draft layout dashboard sudah dikirim untuk review.',
                            ],
                            [
                                'user' => 'ahmad.fauzi@bpkh.go.id',
                                'comment' => 'Masukan awal: perlu highlight proyeksi mingguan.',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Pengujian stress scenario',
                        'description' => 'Simulasikan penarikan dana besar terhadap likuiditas.',
                        'status' => 'todo',
                        'priority' => 'medium',
                        'due_date' => Carbon::now()->addDays(18),
                        'created_by' => 'rafi.pratama@bpkh.go.id',
                        'assignees' => [
                            'rafi.pratama@bpkh.go.id',
                        ],
                        'activities' => [
                            [
                                'user' => 'rafi.pratama@bpkh.go.id',
                                'comment' => 'Menyusun skenario stress bersama tim risiko.',
                            ],
                            [
                                'user' => 'admin@bpkh.go.id',
                                'comment' => 'Mohon dokumentasikan asumsi stress scenario.',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Integrasi laporan harian',
                        'description' => 'Tarik data likuiditas harian dari sistem core banking.',
                        'status' => 'in_progress',
                        'priority' => 'medium',
                        'due_date' => Carbon::now()->addDays(9),
                        'created_by' => 'dita.kusuma@bpkh.go.id',
                        'assignees' => [
                            'dita.kusuma@bpkh.go.id',
                            'ahmad.fauzi@bpkh.go.id',
                        ],
                        'activities' => [
                            [
                                'user' => 'dita.kusuma@bpkh.go.id',
                                'comment' => 'Endpoint laporan harian sudah tersedia di staging.',
                            ],
                            [
                                'user' => 'ahmad.fauzi@bpkh.go.id',
                                'comment' => 'Perlu mapping tambahan untuk saldo akhir.',
                            ],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Audit Trail Penugasan Petugas Haji',
                'description' => 'Tracking aktivitas penugasan petugas haji untuk keperluan audit.',
                'owner' => 'rafi.pratama@bpkh.go.id',
                'members' => [
                    'sri.ramadhani@bpkh.go.id',
                    'admin@bpkh.go.id',
                ],
                'tasks' => [
                    [
                        'title' => 'Template audit penugasan',
                        'description' => 'Susun template audit trail untuk penugasan petugas haji.',
                        'status' => 'done',
                        'priority' => 'low',
                        'due_date' => Carbon::now()->subDays(3),
                        'created_by' => 'rafi.pratama@bpkh.go.id',
                        'assignees' => [
                            'rafi.pratama@bpkh.go.id',
                        ],
                        'activities' => [
                            [
                                'user' => 'rafi.pratama@bpkh.go.id',
                                'comment' => 'Template audit sudah disetujui tim kepatuhan.',
                            ],
                            [
                                'user' => 'admin@bpkh.go.id',
                                'comment' => 'Tambahkan kolom verifikasi supervisor.',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Log aktivitas petugas',
                        'description' => 'Catat perubahan status penugasan dan aktivitas petugas di lapangan.',
                        'status' => 'in_progress',
                        'priority' => 'high',
                        'due_date' => Carbon::now()->addDays(6),
                        'created_by' => 'admin@bpkh.go.id',
                        'assignees' => [
                            'admin@bpkh.go.id',
                            'sri.ramadhani@bpkh.go.id',
                        ],
                        'activities' => [
                            [
                                'user' => 'admin@bpkh.go.id',
                                'comment' => 'Butuh konfirmasi sumber data aktivitas petugas.',
                            ],
                            [
                                'user' => 'sri.ramadhani@bpkh.go.id',
                                'comment' => 'Sumber data bisa diambil dari sistem penugasan pusat.',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Review akses role',
                        'description' => 'Pastikan role hanya bisa melihat data sesuai wilayah tugas.',
                        'status' => 'todo',
                        'priority' => 'medium',
                        'due_date' => Carbon::now()->addDays(11),
                        'created_by' => 'sri.ramadhani@bpkh.go.id',
                        'assignees' => [
                            'sri.ramadhani@bpkh.go.id',
                            'rafi.pratama@bpkh.go.id',
                        ],
                        'activities' => [
                            [
                                'user' => 'sri.ramadhani@bpkh.go.id',
                                'comment' => 'Rencana review akses akan dibahas di rapat mingguan.',
                            ],
                            [
                                'user' => 'rafi.pratama@bpkh.go.id',
                                'comment' => 'Perlu daftar role prioritas untuk audit tahap awal.',
                            ],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($projectsData as $projectData) {
            $owner = $users[$projectData['owner']];

            $project = Project::create([
                'user_id' => $owner->id,
                'name' => $projectData['name'],
                'description' => $projectData['description'],
            ]);

            $memberPayload = collect($projectData['members'])
                ->mapWithKeys(function (string $email) use ($users) {
                    return [$users[$email]->id => ['added_at' => now()]];
                });

            $project->members()->syncWithoutDetaching($memberPayload->all());

            foreach ($projectData['tasks'] as $taskData) {
                $task = Task::create([
                    'project_id' => $project->id,
                    'title' => $taskData['title'],
                    'description' => $taskData['description'],
                    'status' => $taskData['status'],
                    'priority' => $taskData['priority'],
                    'due_date' => $taskData['due_date'],
                    'created_by' => $users[$taskData['created_by']]->id,
                ]);

                $assigneePayload = collect($taskData['assignees'])
                    ->mapWithKeys(fn(string $email) => [$users[$email]->id => ['assigned_at' => now()]]);

                $task->assignees()->sync($assigneePayload->all());

                foreach ($taskData['activities'] as $activity) {
                    TaskActivity::create([
                        'task_id' => $task->id,
                        'user_id' => $users[$activity['user']]->id,
                        'comment' => $activity['comment'],
                    ]);
                }
            }
        }
    }
}

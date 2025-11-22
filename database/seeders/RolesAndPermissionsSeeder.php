<?php

    namespace Database\Seeders;

    use Illuminate\Database\Seeder;
    use Spatie\Permission\Models\Role;
    use Spatie\Permission\Models\Permission;

    class RolesAndPermissionsSeeder extends Seeder
    {
        /**
         * Run the database seeds.
         */
        public function run(): void
        {
            // Reset cache
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            // Buat Roles
            Role::firstOrCreate(['name' => 'Super Admin']);
            Role::firstOrCreate(['name' => 'Admin Prodi']);
            Role::firstOrCreate(['name' => 'Dosen']);
            Role::firstOrCreate(['name' => 'Mahasiswa']);

            // Anda bisa tambahkan permissions di sini jika perlu
            // Contoh:
            // Permission::firstOrCreate(['name' => 'create ruangan']);
            // Permission::firstOrCreate(['name' => 'edit ruangan']);
            
            // $roleAdminProdi = Role::findByName('Admin Prodi');
            // $roleAdminProdi->givePermissionTo(['create ruangan', 'edit ruangan']);
        }
    }
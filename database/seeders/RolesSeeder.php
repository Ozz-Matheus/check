<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $superAdminRole = Role::create(['name' => 'super_admin']);
        $adminRole = Role::create(['name' => 'admin']);
        $auditorRole = Role::create(['name' => 'auditor']);
        $auditedRole = Role::create(['name' => 'audited']);
        $standardRole = Role::create(['name' => 'standard']);
        $basicRole = Role::create(['name' => 'panel_user']);

        $superAdmin = new User;
        $superAdmin->name = 'Super Administrador';
        $superAdmin->email = 's@d.com';
        $superAdmin->password = bcrypt('s@d.com');
        $superAdmin->save();

        $superAdmin->assignRole($superAdminRole);

        $admin = new User;
        $admin->name = 'Administrador';
        $admin->email = 'a@d.com';
        $admin->password = bcrypt('a@d.com');
        $admin->save();

        $admin->assignRole($adminRole);

        $auditorOne = new User;
        $auditorOne->name = 'Auditor One';
        $auditorOne->email = 'ao@d.com';
        $auditorOne->password = bcrypt('ao@d.com');
        $auditorOne->save();

        $auditorOne->assignRole($auditorRole);

        $auditorTwo = new User;
        $auditorTwo->name = 'Auditor Two';
        $auditorTwo->email = 'atw@d.com';
        $auditorTwo->password = bcrypt('atw@d.com');
        $auditorTwo->save();

        $auditorTwo->assignRole($auditorRole);

        $auditorThree = new User;
        $auditorThree->name = 'Auditor Three';
        $auditorThree->email = 'ath@d.com';
        $auditorThree->password = bcrypt('ath@d.com');
        $auditorThree->save();

        $auditorThree->assignRole($auditorRole);

        $standardOne = new User;
        $standardOne->name = 'Estandard One';
        $standardOne->email = 'o@d.com';
        $standardOne->password = bcrypt('o@d.com');
        $standardOne->save();

        $standardOne->assignRole($standardRole);

        $standardTwo = new User;
        $standardTwo->name = 'Estandard Two';
        $standardTwo->email = 'tw@d.com';
        $standardTwo->password = bcrypt('tw@d.com');
        $standardTwo->save();

        $standardTwo->assignRole($standardRole);
        $standardTwo->assignRole($auditedRole);

        $standardThree = new User;
        $standardThree->name = 'Estandard Three';
        $standardThree->email = 'th@d.com';
        $standardThree->password = bcrypt('th@d.com');
        $standardThree->save();

        $standardThree->assignRole($standardRole);
        $standardThree->assignRole($auditedRole);

        $basic = new User;
        $basic->name = 'Usuario';
        $basic->email = 'b@d.com';
        $basic->password = bcrypt('b@d.com');
        $basic->save();

        $basic->assignRole($basicRole);
    }
}

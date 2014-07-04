<?php
class UserTableSeeder extends Seeder {

    public function run()
    {
       DB::table('users')->delete();

        $user = new User;

       /* User::create([
            'id'        => '1',
            'username'    => 'admin1',
            'firstname' => 'admin_fname',
            'lastname' => 'admin_lname',
            'email' => 'admin@gmail.com',
            'password' => '$2y$10$fM./ADP/FL3iECvlJOubkuBs/OPtbbYZns9PnHC8VPz0WVAA4B99q',
            'password_confirmation' => '$2y$10$fM./ADP/FL3iECvlJOubkuBs/OPtbbYZns9PnHC8VPz0WVAA4B99q',
            'confirmation_code' => 'ok',
            'confirmed' => 1,
            'office_id' => 1,
        ]);
        */
        // Seeding of Administrator account
        $user->id = '1';
        $user->username = 'admin1';
        $user->firstname = 'John';
        $user->lastname = 'Cruz' ;
        $user->email = 'jdcruz@gmail.com';
        $user->password = 'admin1';
        $user->password_confirmation = 'admin1';
        $user->confirmation_code = 'ok';
        $user->confirmed = 1;
        $user->office_id = 1;
        $user->save();

        // Seeding of Procurement Personnel
        $user = new User;
        $user->id = '2';
        $user->username = 'person1';
        $user->firstname = 'Jane';
        $user->lastname = 'Doe' ;
        $user->email = 'person@gmail.com';
        $user->password = 'person1';
        $user->password_confirmation = 'person1';
        $user->confirmation_code = 'ok';
        $user->confirmed = 1;
        $user->office_id = 1;
        $user->save();

        // Seeding of Procurement Personnel
        $user = new User;
        $user->id = '3';
        $user->username = 'requis1';
        $user->firstname = 'Joseph';
        $user->lastname = 'Elorde' ;
        $user->email = 'requis@gmail.com';
        $user->password = 'requis1';
        $user->password_confirmation = 'requis1';
        $user->confirmation_code = 'ok';
        $user->confirmed = 1;
        $user->office_id = 2;
        $user->save();
/*
        $user = new User;
        $user->id = '4';
        $user->username = 'arben1';
        $user->firstname = 'Arben';
        $user->lastname = 'Hombre' ;
        $user->email = 'arben@gmail.com';
        $user->password = 'arben1';
        $user->password_confirmation = 'arben1';
        $user->confirmation_code = 'ok';
        $user->confirmed = 1;
        $user->office_id = 2;
        $user->save();
        */
    }
}
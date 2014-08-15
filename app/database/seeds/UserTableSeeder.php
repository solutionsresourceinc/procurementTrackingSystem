<?php
class UserTableSeeder extends Seeder {

    public function run()
    {
       DB::table('users')->delete();

        $user = new User;

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

        // ACCOUNTS FOR TESTING PURPOSES
        $controlId=4;

        $user = new User;
        $user->id = $controlId++;
        $user->username = 'edsample';
        $user->firstname = 'Ed';
        $user->lastname = 'Sample' ;
        $user->email = 'edsample@gmail.com';
        $user->password = 'edsample';
        $user->password_confirmation = 'edsample';
        $user->confirmation_code = 'ok';
        $user->confirmed = 1;
        $user->office_id = 1;
        $user->save();

        $user = new User;
        $user->id = $controlId++;
        $user->username = 'peachysample';
        $user->firstname = 'Peachy';
        $user->lastname = 'Sample';
        $user->email = 'peachysample@gmail.com';
        $user->password = 'peachysample';
        $user->password_confirmation = 'peachysample';
        $user->confirmation_code = 'ok';
        $user->confirmed = 1;
        $user->office_id = 1;
        $user->save();

        $user = new User;
        $user->id = $controlId++;
        $user->username = 'gensample';
        $user->firstname = 'Gen';
        $user->lastname = 'Sample' ;
        $user->email = 'gensample@gmail.com';
        $user->password = 'gensample';
        $user->password_confirmation = 'gensample';
        $user->confirmation_code = 'ok';
        $user->confirmed = 1;
        $user->office_id = 1;
        $user->save();

        $user = new User;
        $user->id = $controlId++;
        $user->username = 'jhosample';
        $user->firstname = 'Jho';
        $user->lastname = 'Sample' ;
        $user->email = 'jhosample@gmail.com';
        $user->password = 'jhosample';
        $user->password_confirmation = 'jhosample';
        $user->confirmation_code = 'ok';
        $user->confirmed = 1;
        $user->office_id = 1;
        $user->save();

        $user = new User;
        $user->id = $controlId++;
        $user->username = 'jdcruz';
        $user->firstname = 'Juan';
        $user->lastname = 'Dela Cruz' ;
        $user->email = 'jdcruzx@gmail.com';
        $user->password = 'jdcruz';
        $user->password_confirmation = 'jdcruz';
        $user->confirmation_code = 'ok';
        $user->confirmed = 1;
        $user->office_id = 4;
        $user->save();
    }
}
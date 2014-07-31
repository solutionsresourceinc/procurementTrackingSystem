<?php
class RoleSeeder extends Seeder {

    public function run()
    {
        DB::delete('delete from roles');
        
        DB::insert('insert into roles (name) values (?)', array('Requisitioner'));
        DB::insert('insert into roles (name) values (?)', array('Procurement Personnel'));
        DB::insert('insert into roles (name) values (?)', array('Administrator'));


    }
}
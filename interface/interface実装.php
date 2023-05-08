<?php

namespace temp;

class User {

    private string $name;

    public function getName() : string {
        return $this->name;
    }
}

interface Repository {

    public function save();
}

interface UserRepository extends Repository {

    /**
     * Userを保存する関数
     *
     * @param User $user
     * @return void
     */
    public function register(User $user) : void;
}

class SQLUserRepository implements UserRepository {

    public function save() {
    }

    public function register(User $user) : void {
        $sql->......
    }
}

class JsonUserRepository implements UserRepository {

    public function save() {
    }

    public function register(User $user) : void {
        file_put_contents(....)
    }
}

class Main {

    private UserRepository $userRepository;

    public function __construct() {
        $this->userRepository = new JsonUserRepository();//ここを変更すれば処理が切り替わる　
    }

    public function hoge(User $user) {
        $this->userRepository->register($user);
        $this->userRepository->save();//この時点ではuserRepositoryはしか知らない
    }
}
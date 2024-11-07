<?php


namespace iutnc\deefy\users;
use iutnc\deefy\exception as E;
use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\audio\tracks as T;

class User{


    private int $id;
    private string $email;
    private int $role;


    public function __construct(string $user_email)
    {
        $user = DeefyRepository::getInstance()->getUserByEmail($user_email);

        $this->id = $user['id'];
        $this->email = $user_email;
        $this->role = $user['role'];
    }


    public function __get(string $attribut): mixed{
        if(property_exists($this,$attribut)){
            return $this->$attribut;
        }
        throw new E\InvalidPropertyNameException("$attribut : invalid property");
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): int {
        return $this->id;
    }

}

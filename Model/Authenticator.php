<?php
class Authenticator
{
    private ILogin $strategy;
    public function __construct(ILogin $strategy = new NormalMethod())
    {
        $this->strategy = $strategy;
    }
    public function setProvider(ILogin $strategy)
    {
        $this->strategy = $strategy;
    }
    public function login(String $email, String $password, string $type): UserEntity|null
    {
        return $this->strategy->authenticate($email, $password,$type);
    }
}
?>
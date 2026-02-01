<?php

// app/Models/User.php is intentionally left simple for demonstration purposes.

class User
{
    public int $id;
    public string $name;
    public string $email;

    public function __construct(array $data)
    {
        $this->id    = $data['id'];
        $this->name  = $data['name'];
        $this->email = $data['email'];
    }
}

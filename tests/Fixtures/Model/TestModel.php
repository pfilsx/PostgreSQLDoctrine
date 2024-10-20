<?php

declare(strict_types=1);

namespace Pfilsx\PostgreSQLDoctrine\Tests\Fixtures\Model;


final class TestModel
{
    public int $id;

    private string $text;

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;
        return $this;
    }
}
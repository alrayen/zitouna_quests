<?php

class Question {

    // --- Properties ---
    private int $id_question;
    private ?int $id_quiz; 
    private string $text;
    private string $option1;
    private string $option2;
    private string $option3;
    private string $option4;
    private string $bonne;

    public function __construct(?int $id_question, int $id_quiz, string $text, string $option1, string $option2, string $option3, string $option4, string $bonne) {
        $this->id_question = $id_question;
        $this->id_quiz = $id_quiz;
        $this->text = $text;
        $this->option1 = $option1;
        $this->option2 = $option2;
        $this->option3 = $option3;
        $this->option4 = $option4;
        $this->bonne = $bonne;
    }

    public function getIdQuestion(): ?int {
        return $this->id_question;
    }

    public function getIdQuiz(): int {
        return $this->id_quiz;
    }

    public function getTextQuestion(): string {
        return $this->text;
    }

    public function getOption1(): string {
        return $this->option1;
    }

    public function getOption2(): string {
        return $this->option2;
    }

    public function getOption3(): string {
        return $this->option3;
    }

    public function getOption4(): string {
        return $this->option4;
    }

    public function getBonneReponse(): string {
        return $this->bonne;
    }

    public function setTextQuestion(string $text): void {
        $this->text = $text;
    }

    public function setOption1(string $option1): void {
        $this->option1 = $option1;
    }

    public function setOption2(string $option2): void {
        $this->option2 = $option2;
    }

    public function setOption3(string $option3): void {
        $this->option3 = $option3;
    }

    public function setOption4(string $option4): void {
        $this->option4 = $option4;
    }

    public function setBonneReponse(string $bonne): void {
        $this->bonne = $bonne;
    }
}
?>
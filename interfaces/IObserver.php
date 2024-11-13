<?php
interface IObserver {
    public function update(ISubject $subject): void;
}
?>

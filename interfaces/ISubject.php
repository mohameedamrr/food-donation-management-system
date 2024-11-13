<?php
interface ISubject {
    public function addObserver(IObserver $observer): void;
    public function removeObserver(IObserver $observer): void;
    public function notifyObservers(): void;
}
?>

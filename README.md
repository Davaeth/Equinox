Projekt wykonany przez studenta Patryka Połeć.
Projekt jest pracą zaliczeniową do przedmiotu pt. Tworzenie aplikacji internetowych.

Aplikacja wykorzystuje język PHP wraz z frameworkiem Symfony.
Do pracy z aplikacją i przetrzymywania danych wykorzystana została baza danych MySQL.

Aplikacja tworzona jest w pełni fanowsko do gry Super Spell Heroes.
Użytkownik ma do dyspozycji 3 formualrze (nie lciząc formularzy rejestracyjnych).
Kazdy formularz odpowiada za inną część gry, tj. postać, zaklęcie, element magii.
Formularze dostosowane są do standardów z gry.
Dzięki tej aplikacji gracze mogą tworzyć swoje własne elementy rozgrywki jako pomysły do gry.
Ochrona przed niezalogowanym użytkownikiem wykonana jest przy pomocy sesji.

Kroki niezbędne do uruchomienia projektu:

1. Wypakować zawartość spakowanego projektu do folderu htdocs w folderze XAMPP.

2. Uruchomić XAMPPa.
	2a. Uruchmoić Apache or MySQL.

3. Przejść do panelu php my admin (można to zrobić za pomocą przycisku "Admin" w XAMPPie przy panelu MySQL)

4. Utworzyć pustą bazę danych o nazwie 'equinox'.

5. Przejść do pliku '.env' w folderze głównym projektu.
	5a. Sprawdzić czy w linijce 27 widnieje odpowiedni login oraz hasło do konta MySQL.
	
6. Po poprawnym wykonaniu punktu 5 (wtedy i tylko wtedy) przejść do folderu głównego z projektu w wierszu poleceń systemu.
	(W przypadku konieczności zmiany partycji na systemu operacjnym windows wpisać np. 'F: <ENTER>'.)
	6a. Wpisać polecenie 'php bin/console doctrine:migrations:diff' - polecenie to utworzy nową wersję stanu bazy danych.
	6b. Wpisac polecenie 'php bin/console doctrine:migrations:migrate' a następnie wpisać 'y' przy pytaniu o potwierdzenie migracji.
	6c. Uruchomić serwer lokalny poleceniem 'php bin/console server:run'.
	6d. Wpisać w przeglądarce adres 'localhost:8000'.
	
UWAGA! W przypadku błędów podczas próby migracji należy upewnić się, iż baza na pewno posiada odpowiednią nazwię 'equinox'. Upewnić się również
iż jest ona pusta. W przypadku gdy nie jest pusta należy usunąć całą jej zawartość, bądź stworzyć ją od nowa oraz usunąć zawartość folderu 
'Migrations' zanjdującego w folderze 'src', który znajduje się w folderze głównym projektu. Następnie powtórzyć krok 6.

<?php

namespace Entity;
use DateTime;
use InvalidArgumentException;
use JsonSerializable;

enum Role: string {
    case UTENTE = 'User';
    case AMMINISTRATORE = 'Admin';
}

/**
 * Class EUser
 * Represents the user with their main properties.
 */
class EUser implements JsonSerializable {
    /** 
     * IDENTIFIERS
     * @var ?int User ID, can be null if not assigned. 
     */
    private ?int $idUser;

    /** 
     * @var int The unique identifier for a single Review, managed by the database.
     */
    private ?int $idReview;

    /**
     * METADATA
     * @var string Username of the user, protected access. 
     */
    protected string $username;

    /** 
     * @var string Email of the user, protected access. 
     */
    protected string $email;

    /** 
     * @var string Password of the user, protected access. 
     */
    protected string $password;

    /** 
     * @var bool Tracks whether the password has been changed. Private access. 
     */
    public bool $passwordChanged = false;

    /** 
     * @var string|null URL of the users's image. Nullable. 
     */
    public ?string $image;

    /** 
     * @var bool Indicates if the user is banned. 
     */
    private bool $ban;

    /** 
     * @var array User's reservations. 
     */
    private array $reservations;

    /** 
     * @var array User's credit cards. 
     */
    private array $creditCards;

    /**
     * PERSONAL_INFORMARTION 
     * @var string First name of the user. 
     */
    public string $name;

    /** 
     * @var string Last name of the user. 
     */
    public string $surname;

    /** 
     * @var DateTime Date of birth of the user. 
     */
    public DateTime $birthDate;

    /** 
     * @var string Phone number of the user. 
     */
    public string $phone;

    /**
     * @var Role descrive the role of the Person
     */
    private Role $role;

    /**
     * Constructor for the EUser class with validation checks.
     *
     * @param int $idUser User ID, can be null.
     * @param int $idReview Review ID, can be an optional.
     * @param string $username Username
     * @param string $email User's email
     * @param string $password User's password
     * @param string|null $image URL of the user's image (optional)
     * @param bool $ban Indicates if the user is banned (default: false).
     * @param string $name First name of the user
     * @param string $surname Last name of the user
     * @param DateTime $birthDate User's date of birth
     * @param string $phone User's phone number
     * @var Role $role descrive the role of the person
     * @throws InvalidArgumentException if any input is invalid.
     */
    public function __construct(
        ?int $idUser,
        ?int $idReview,
        string $username,
        string $email,
        string $password,
        ?string $image,
        bool $ban = false,
        string $name,
        string $surname,
        DateTime $birthDate,
        string $phone,
        Role $role
    ) {
        if (empty($username)) {
            throw new InvalidArgumentException("Username cannot be empty.");
        }
        if (empty($email)) {
            throw new InvalidArgumentException("Email cannot be empty.");
        }

        $this->idUser = $idUser;
        $this->idReview = $idReview;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->image = $image;
        $this->ban = $ban;
        $this->reservations = [];
        $this->creditCards = [];
        $this->name = $name;
        $this->surname = $surname;
        $this->birthDate = $birthDate;
        $this->phone = $phone;
        $this->role = $role;
    }

    /**
     * Gets the user ID.
     *
     * @return ?int User ID or null if not assigned.
     */
    public function getIdUser(): ?int{
        return $this->idUser;
    }

    /**
     * Sets the user ID.
     *
     * @param ?int $idUser User ID to set, can be null.
     */
    public function setIdUser(?int $idUser): void {
        $this->idUser = $idUser;
    }

    /**
     * Gets the user's review.
     *
     * @return int User's review.
     */
    public function getIdReview(): ?int {
        return $this->idReview;
    }

    /**
     * Sets the unique identifier for the Review.
     *
     * @param int|null $idReview The ID to set.
     */
    public function setIdReview(?int $idReview): void {
        $this->idReview = $idReview;
    }

    /**
     * Gets the username.
     *
     * @return string The user's username.
     */
    public function getUsername(): string {
        return $this->username;
    }

    /**
     * Sets the username.
     *
     * @param string $username The username to set.
     */
    public function setUsername(string $username): void {
        if (empty($username)) {
            throw new InvalidArgumentException("Username non può essere vuoto");
        }
        $this->username = $username;
    }

    /**
     * Gets the email.
     *
     * @return string The user's email.
     */
    public function getEmail(): string {
        return $this->email;
    }

    /**
     * Sets the email with validation.
     *
     * @param string $email The email to set.
     * @throws InvalidArgumentException if email is not valid.
     */
    public function setEmail(string $email): void {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Email non valida");
        }
        $this->email = $email;
    }

    /**
     * Gets the password.
     *
     * @return string The user's password.
     */
    public function getPassword(): string {
        return $this->password;
    }

    /**
     * Sets the password and marks it as changed.
     *
     * @param string $password The password to set.
     */
    const MIN_PASSWORD_LENGTH = 8;  // Lunghezza minima della password
    const MAX_PASSWORD_LENGTH = 64; // Lunghezza massima della password

    public function setPassword(string $password): void {
        // Controllo della lunghezza minima
        if (strlen($password) < self::MIN_PASSWORD_LENGTH) {
            throw new InvalidArgumentException("La password deve contenere almeno " . self::MIN_PASSWORD_LENGTH . " caratteri.");
        }

        // Controllo della lunghezza massima (opzionale)
        if (strlen($password) > self::MAX_PASSWORD_LENGTH) {
            throw new InvalidArgumentException("La password non deve superare i " . self::MAX_PASSWORD_LENGTH . " caratteri.");
        }

        // Controllo presenza di almeno una lettera maiuscola
        if (!preg_match('/[A-Z]/', $password)) {
            throw new InvalidArgumentException("La password deve contenere almeno una lettera maiuscola.");
        }

        // Controllo presenza di almeno una lettera minuscola
        if (!preg_match('/[a-z]/', $password)) {
            throw new InvalidArgumentException("La password deve contenere almeno una lettera minuscola.");
        }

        // Controllo presenza di almeno un numero
        if (!preg_match('/[0-9]/', $password)) {
            throw new InvalidArgumentException("La password deve contenere almeno un numero.");
        }

        // Controllo presenza di almeno un carattere speciale
        if (!preg_match('/[\W_]/', $password)) {
            throw new InvalidArgumentException("La password deve contenere almeno un carattere speciale (es. @, #, $, etc.).");
        }

        // Se passa tutti i controlli, imposta la password
        $this->password = $password;
        $this->passwordChanged = true;
    }

    /**
     * Checks if the password has been changed.
     *
     * @return bool True if the password has been changed, false otherwise.
     */
    public function isPasswordChanged(): bool {
        return $this->passwordChanged;
    }

    /**
     * Gets the image URL of the user.
     *
     * @return string|null The URL of the user's image.
     */
    public function getImage(): ?string {
        return $this->image;
    }

    /**
     * Sets the image URL of the user.
     *
     * @param string|null $image The image URL to set.
     */
    public function setImage(?string $image): void {
        if ($image !== null && !filter_var($image, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException("URL dell'immagine non valido");
        }
        $this->image = $image;
    }

    /**
     * Checks if the user is banned.
     *
     * @return bool True if the user is banned, false otherwise.
     */
    public function getBan(): bool
    {
        return $this->ban;
    }

    /**
     * Sets the ban status of the user.
     *
     * @param bool $ban True to ban the user, false to unban.
     */
    public function setBan(bool $ban): void {
        $this->ban = $ban;
    }

    /**
     * Gets the user's reservations.
     *
     * @return array User's reservations.
     */
    public function getReservations(): array {
        return $this->reservations;
    }

    /**
     * Adds a reservation to the user's reservations.
     *
     * @param EReservation $reservation The reservation to add.
     */
    public function addReservation(EReservation $reservation): void {
        $this->reservations[] = $reservation;
    }

    /**
     * Removes a reservation from the user's reservations.
     *
     * @param EReservation $reservation The reservation to remove.
     */
    public function removeReservation(EReservation $reservation): void {
        $this->reservations = array_filter($this->reservations, fn($r) => $r !== $reservation);
    }

    /**
     * Gets the user's credit cards.
     *
     * @return array User's credit cards.
     */
    public function getCreditCards(): array
    {
        return $this->creditCards;
    }

    /**
     * Adds a credit card to the user's credit cards.
     *
     * @param ECreditCard $creditCard The credit card to add.
     */
    public function addCreditCard(ECreditCard $creditCard): void
    {
        $this->creditCards[] = $creditCard;
    }

    /**
     * Removes a credit card from the user's credit cards.
     *
     * @param ECreditCard $creditCard The credit card to remove.
     */
    public function removeCreditCard(ECreditCard $creditCard): void
    {
        $this->creditCards = array_filter($this->creditCards, fn($cc) => $cc !== $creditCard);
    }

    /**
     * Gets the first name of the user.
     *
     * @return string The user's first name.
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Sets the first name of the user.
     *
     * @param string $name The first name to set.
     * @throws InvalidArgumentException if name is empty.
     */
    public function setName(string $name): void {
        if (empty($name)) {
            throw new InvalidArgumentException("Il nome non può essere vuoto");
        }
        $this->name = $name;
    }

    /**
     * Gets the surname of the user.
     *
     * @return string The user's surname.
     */
    public function getSurname(): string {
        return $this->surname;
    }

    /**
     * Sets the surname of the user.
     *
     * @param string $surname the surname to set.
     */
    public function setSurname(string $surname): void {
        if (empty($surname)) {
            throw new InvalidArgumentException("Il cognome non può essere vuoto");
        }
        $this->surname = $surname;
    }

    /**
     * Gets the date of birth of the user.
     *
     * @return string The user's date of birth.
     */
    public function getBirthDate(): string {
        return $this->birthDate->format('d-m-Y');
    }

    /**
     * Sets the date of birth of the user.
     *
     * @param DateTime $birthDate The date of birth to set.
     * @throws InvalidArgumentException if birth date is in the future or underage.
     */
    public function setBirthDate(DateTime $birthDate): void {
        $now = new DateTime();
        if ($birthDate > $now) {
            throw new InvalidArgumentException("La data di nascita non può essere nel futuro");
        }

        // Calcola l'età minima (es. 18 anni)
        $ageLimit = (clone $now)->modify('-18 years');
        if ($birthDate > $ageLimit) {
            throw new InvalidArgumentException("Devi avere almeno 18 anni");
        }

        $this->birthDate = $birthDate;
    }

    /**
     * Gets the phone number of the person.
     *
     * @return string The person's phone number.
     */
    public function getPhone(): string {
        return $this->phone;
    }

    /**
     * Sets the phone number of the person.
     *
     * @param string $phone The phone number to set.
     * @throws InvalidArgumentException if phone number is not valid.
     */
    public function setPhone(string $phone): void {
        // Controllo per numeri telefonici internazionali: + seguito da 7 a 15 cifre
        if (!preg_match('/^\+?[1-9]\d{6,14}$/', $phone)) {
            throw new InvalidArgumentException("Numero di telefono non valido. Deve essere un numero di telefono internazionale valido.");
        }
        $this->phone = $phone;
    }

    /**
     * Gets the person's role.
     *
     * @return Role person's role.
     */
    public function getRole(): string {
        return $this->role->value;
    }

    /**
     * Sets the peroson's role.
     *
     * @param Role $role person's role.
     */
    public function setRole(Role $role): void {
        $this->role = $role;
    }


    /**
     * Implementation of the jsonSerialize method.
     *
     * @return array Associative array of the object's properties.
     */
    public function jsonSerialize(): array {
        return [
            'idUser' => $this->idUser,
            'idReview' => $this->idReview,
            'username' => $this->getUsername(),
            'email' => $this->getEmail(), // Include l'email se necessario
            // Nota: Evitiamo di serializzare la password per motivi di sicurezza
            'image' => $this->getImage(),
            'ban' => $this->ban,
            'reservations' => $this->reservations,
            'creditCards' => $this->creditCards,
            'name' => $this->getName(),
            'surname' => $this->getSurname(),
            'birthDate' => $this->getBirthDate(),
            'phone' => $this->getPhone(),
            'role' => $this->getRole()
        ];
    }

}
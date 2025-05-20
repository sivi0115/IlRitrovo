<?php

namespace Controller;

use DateTime;
use Entity\ECreditCard;
use Entity\EPayment;
use Exception;
use Foundation\FCreditCard;
use Foundation\FPayment;

class CPayment
{

    /**
     * Aggiunge una nuova carta di credito all'utente.
     *
     * @param int $idUser L'ID dell'utente.
     * @param string $number Il numero della carta.
     * @param int $cvv Il CVV della carta.
     * @param string $expiration La data di scadenza della carta (formato MM/YY).
     * @param string $holder Il nome del titolare della carta.
     * @param string $type Il tipo di carta (es. Visa, MasterCard).
     * @return bool True se l'aggiunta ha successo, false altrimenti.
     * @throws Exception Se si verifica un errore durante l'aggiunta della carta.
     */
    public function aggiungiCarta(int $idUser, string $number, int $cvv, string $expiration, string $holder, string $type): bool
    {
        try {
            $expirationDate = DateTime::createFromFormat('m/y', $expiration);
            $creditCard = new ECreditCard(null, $number, $cvv, $expirationDate, $holder, $type, $idUser);
            return FCreditCard::storeCreditCard($creditCard, $idUser);
        } catch (Exception $e) {
            throw new Exception("Errore durante l'aggiunta della carta: " . $e->getMessage());
        }
    }

    /**
     * Rimuove una carta di credito dall'utente.
     *
     * @param int $idUser L'ID dell'utente.
     * @param string $number Il numero della carta da rimuovere.
     * @return bool True se la rimozione ha successo, false altrimenti.
     * @throws Exception Se si verifica un errore durante la rimozione della carta.
     */
    public function rimuoviCarta(int $idUser, string $number): bool
    {
        try {
            return FCreditCard::deleteCreditCard($number, $idUser);
        } catch (Exception $e) {
            throw new Exception("Errore durante la rimozione della carta: " . $e->getMessage());
        }
    }
    /**
     * Crea un nuovo pagamento.
     *
     * @param float $total L'importo totale del pagamento.
     * @param string $method Il metodo di pagamento.
     * @param string $state Lo stato del pagamento.
     * @param int $idCreditCard L'ID della carta di credito.
     * @param int $idReservation L'ID della prenotazione.
     * @return int L'ID del pagamento creato.
     * @throws Exception Se si verifica un errore durante la creazione del pagamento.
     */
    public function createPayment(float $total, string $method, string $state, int $idCreditCard, int $idReservation): int
    {
        try {
            $payment = new EPayment(null, $total, $method, new DateTime(), $state, $idCreditCard, $idReservation);
            return FPayment::storePayment($payment);
        } catch (Exception $e) {
            throw new Exception("Errore durante la creazione del pagamento: " . $e->getMessage());
        }
    }

    /**
     * Effettua un pagamento.
     *
     * @param float $total L'importo totale del pagamento.
     * @param string $method Il metodo di pagamento.
     * @param int $idCreditCard L'ID della carta di credito.
     * @param int $idReservation L'ID della prenotazione.
     * @return int L'ID del pagamento effettuato.
     * @throws Exception Se si verifica un errore durante l'effettuazione del pagamento.
     */
    public function effettuaPagamento(float $total, string $method, int $idCreditCard, int $idReservation): int
    {
        try {
            // Assumendo che lo stato iniziale del pagamento sia "pending"
            return $this->createPayment($total, $method, 'pending', $idCreditCard, $idReservation);
        } catch (Exception $e) {
            throw new Exception("Errore durante l'effettuazione del pagamento: " . $e->getMessage());
        }
    }

    /**
     * Conferma un pagamento.
     *
     * @param int $idPayment L'ID del pagamento da confermare.
     * @return bool True se la conferma ha successo, false altrimenti.
     * @throws Exception Se si verifica un errore durante la conferma del pagamento.
     */
    public function confermaPagamento(int $idPayment): bool
    {
        try {
            $payment = FPayment::loadPayment((array)$idPayment);
            if (!$payment) {
                throw new Exception("Pagamento non effettuato.");
            }
            $payment->setState('completed');
            return FPayment::updatePayment($payment);
        } catch (Exception $e) {
            throw new Exception("Errore durante la conferma del pagamento: " . $e->getMessage());
        }
    }

    /**
     * Restituisce la cronologia dei pagamenti di un utente.
     *
     * @param int $idUser L'ID dell'utente.
     * @return array Un array di oggetti EPayment.
     * @throws Exception Se si verifica un errore durante il caricamento della cronologia.
     */
    public function cronologiaPagamenti(int $idUser): array
    {
        try {
            return $this->getPaymentsByUser($idUser);
        } catch (Exception $e) {
            throw new Exception("Errore durante il caricamento della cronologia dei pagamenti: " . $e->getMessage());
        }
    }

    /**
     * Imposta una carta di credito come predefinita per un utente.
     *
     * @param int $idCreditCard L'ID della carta di credito.
     * @param int $idUser L'ID dell'utente.
     * @return bool True se l'operazione ha successo, false altrimenti.
     * @throws Exception Se si verifica un errore durante l'impostazione della carta predefinita.
     */
    public function impostaCartaPredefinita(int $idCreditCard, int $idUser): bool
    {
        try {
            return FCreditCard::setDefault($idCreditCard, $idUser);
        } catch (Exception $e) {
            throw new Exception("Errore durante l'impostazione della carta predefinita: " . $e->getMessage());
        }
    }

    /**
     * Aggiorna i dati di una carta di credito.
     *
     * @param int $idUser L'ID dell'utente.
     * @param string $number Il numero della carta.
     * @param int $cvv Il nuovo CVV della carta.
     * @param string $expiration La nuova data di scadenza della carta (formato MM/YY).
     * @param string $holder Il nuovo nome del titolare della carta.
     * @param string $type Il nuovo tipo di carta (es. Visa, MasterCard).
     * @return bool True se l'aggiornamento ha successo, false altrimenti.
     * @throws Exception Se si verifica un errore durante l'aggiornamento della carta.
     */
    public function aggiornaCarta(int $idUser, string $number, int $cvv, string $expiration, string $holder, string $type): bool
    {
        try {
            $expirationDate = DateTime::createFromFormat('m/y', $expiration);
            $creditCard = new ECreditCard(null, $number, $cvv, $expirationDate, $holder, $type, $idUser);
            return FCreditCard::updateCreditCard($creditCard, $idUser);
        } catch (Exception $e) {
            throw new Exception("Errore durante l'aggiornamento della carta: " . $e->getMessage());
        }
    }
    /**
     * Restituisce tutti i pagamenti di un utente.
     *
     * @param int $idUser L'ID dell'utente.
     * @return array Un array di oggetti EPayment.
     * @throws Exception Se si verifica un errore durante il caricamento dei pagamenti.
     */
    public function getPaymentsByUser(int $idUser): array
    {
        try {
            return FPayment::loadPaymentByUser($idUser);
        } catch (Exception $e) {
            throw new Exception("Errore durante il caricamento dei pagamenti: " . $e->getMessage());
        }
    }



}
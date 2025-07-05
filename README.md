# 🍽️ Il Ritrovo - Progetto di Programmazione per il Web

**Il Ritrovo** è una simulazione di ristorante sviluppata come progetto per l'esame di *Programmazione per il Web* presso l’Università degli Studi dell’Aquila.  
Il sito consente agli utenti di prenotare tavoli e sale online, mentre un amministratore ha accesso alle funzionalità di gestione del ristorante.

## 👥 Tipologie di Utenti

- **Utente**: può registrarsi, effettuare prenotazioni di tavoli e sale, gestire il proprio profilo e lasciare recensioni.
- **Amministratore**: ha accesso a una sezione riservata per gestire utenti, prenotazioni e rispondere alle recensioni.

## 🛠️ Installazione del Progetto

1. **Decomprimere** l'archivio `.zip` del progetto.
2. Spostare la cartella ottenuta all'interno della directory `htdocs`.
3. Verificare che la cartella abbia **permessi di scrittura** per tutti gli utenti.
4. Importare il database:

   - Creare un nuovo database chiamato `ilritrovo` in **MySQL**.
   - Importare il file `.sql` presente nella repository (è l’unico file `.sql` disponibile).

## ⚙️ Configurazione del Database

- **Username**: `root`  
- **Password**: *(vuota)*

Se necessario, modificare le credenziali nel file `config.php` per adattarle alla propria configurazione locale.

## 🧪 Account di Test

Per facilitare l’esplorazione del progetto, sono disponibili alcuni account predefiniti:

| Tipologia | Email                    | Password      |
|-----------|--------------------------|---------------|
| Admin     | sidigiu01@gmail.com      | Password123!  |
| Utente    | marioRossi@gmail.com     | Password123!  |
| Utente    | luigiVerdi@gmail.com     | Password123!  |
| Utente    | lunaNeri@gmail.com       | Password123!  |

## 📚 Documentazione

Tutta la documentazione prodotta durante lo sviluppo del progetto è consultabile all'interno della cartella `documentation/`.

## 🧑‍💻 Autori

Progetto sviluppato da due studenti dell’Università degli Studi dell’Aquila per l’esame di Programmazione per il Web.

---


# A.R.G.U.S

## List of next features to implement:
As of V 0.0.1.2:
- [x] Add new aiml files to ARGUS
- [x] Prepare Hermes-class for use with OpenWeather-API
- [x] Minor changes to database structure
- [x] Minor performance raise

## List of things to do:
As of V 0.0.1.4:
### General
- [ ] Create a ARGUS vocabulary for future conversations (maybe automatic?)
- [x] Use AIML 2.0 for future AIML files (changed AIML-Template already)
- [x] Remove information needed for database connection from Hermes to
      write a file which contains those
- [x] Connect do ARGUS via SSH to change some settings
- [ ] Let ARGUS be able to have a mood which is connected to his answers
- [x] Part ARGUS in different classes with different tasks
- [x] Write a handler for creating a logfile on encounting an error
- [ ] Implement all the english aiml files for english conversations
- [x] Create multiple logfiles for logging different things (eg.: Messages
      and/or connections and so forth)
- [x] Put the whole cURL thing into a different class to slim the index.php
- [ ] Add the possibility to teach ARGUS via messages (maybe a cURL call to teach.php)
- [ ] Implement correct error reporting and remove the dirty
      functions for logging and checking errors
- [ ] Add a badged picture for ARGUS-Facebookpage
- [x] Remove the problems with linebreaks
- [ ] Use proper error_reporting instead of self written dirty log classes
- [x] Add a whole new class for exception handling
- [ ] Create a table for error codes and their meanings
- [ ] Add the @-symbol to suppress a die after an error
- [ ] Add misspelled words, to make ARGUS more flexible

### Hermes
- [ ] Change the method of recognition by saving the convo id
      in an text file with the user id provided by the facebook messenger
      to then read out that convo_id for further use and recognition
- [x] Let Hermes accept german special characters (ä,ö,ü,ß)
- [ ] ARGUS / Hermes should answer different also depending on the relationship
      to the user
- [x] Create a new convo_id whenever another user writes to ARGUS
      (integrate a user detection sytem)
- [x] Let Hermes just be the message delivery system and all other tasks
      be handled by other classes
- [x] Change the way ARGUS creates conversation_ids

### Pythia
- [ ] Change Pythia to let the user change bot properties
- [ ] Add new bot properties
- [ ] Maybe teach ARGUS / Hermes via Pythia

### Athene
- [ ] Add first function to Athene

### Zeus
- [ ] Add first function to Zeus

### Dionysos
- [ ] Add first function to Dionysos

### Lachesis
- [ ] Create a API key for Lachesis
- [ ] Test the api
- [ ] Add first function to Lachesis

### Dumbster
- [ ] Move the requests to the right classes and remove them from the index

### Classes and functions
A brief overview of planned functions and classes
* [ ] Zeus.class.php for weather predictions via 
      OpenWeathermaps-API
* [x] Hermes.class.php just for connection with the other APIs and 
      sending our constructed message
* [x] Pythia.class.php for testing database querys inside classes
* [ ] Athene.class.php for testing the WolframAlpha-API and getting
      results for asked questions.
* [ ] Dionysos.class.php for request regarding food services
* [ ] Maybe some kind of load dumpster class that removes
      al the dirty coding sh** from the index to a separate class
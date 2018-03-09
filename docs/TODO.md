# A.R.G.U.S

## List of next features to implement:
As of 2018-02-12 (V 0.0.1.2):
- [x] Add new aiml files to ARGUS
- [ ] Complete greeting.aiml
- [x] Prepare Hermes-class for use with OpenWeather-API
- [x] Minor changes to database structure
- [x] Minor performance raise

## List of things to do:
As of 2018-02-15 (V 0.0.1.4):
- [ ] Changes to greeting.aiml for every possible greeting
- [ ] Create a ARGUS vocabulary for future conversations (maybe automatic?)
- [ ] Change the method of recognition by saving the convo id
      in an text file with the user id provided by the facebook messenger
      to then read out that convo_id for further use and recognition 
- [x] Let ARGUS accept german special characters (ä,ö,ü,ß)
- [x] Use AIML 2.0 for future AIML files (changed AIML-Template already)
- [x] Remove information needed for database connection from Hermes to
      write a file which contains those
- [ ] Connect do ARGUS via SSH to change some settings
- [ ] Let ARGUS be able to have a mood which is connected to his answers
- [ ] ARGUS should answer different also depending on the relationship
      to the user
- [x] Create a new convo_id whenever another user writes to ARGUS
      (integrate a user detection sytem)
- [x] Part ARGUS in different classes with different tasks
- [x] Let Hermes just be the message delivery system and all other tasks
      be handled by other classes
- [x] Write a handler for creating a logfile on encounting an error
- [x] Change the way ARGUS creates conversation_ids
- [ ] Implement all the english aiml files for english conversations
- [ ] Create multiple logfiles for logging different things (eg.: Messages
      and/or connections and so forth)
- [x] Put the whole cURL thing into a different class to slim the index.php
- [ ] Use proper error_reporting instead of self written dirty log classes
- [ ] Change Pythia and Hermes to let the user change and add bot properties
- [ ] Add the possibility to teach ARGUS via messages (maybe a cURL call to teach.php)
- [ ] Add first function to Zeus
- [ ] Add first function to Athene
- [ ] Add first function to Dionysos

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
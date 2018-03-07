# A.R.G.U.S

## List of next features to implement:
As of 2018-02-12 (V 0.0.1.2):
- [x] Add new aiml files to ARGUS                                           -> Done.
- [ ] Complete greeting.aiml                                                -> still to come.
- [x] Prepare Hermes-class for use with OpenWeather-API                     -> Done.
- [x] Minor changes to database structure                                   -> Done.
- [x] Minor performance raise                                               -> Done.

## List of things to do:
As of 2018-02-15 (V 0.0.1.3):
- [ ] Changes to greeting.aiml for every possible greeting                   -> half done.
- [ ] Create a ARGUS vocabulary for future conversations (maybe automatic?)  -> still to come.
- [ ] Change the method of recognition by saving the convo id                -> still to come.
     in an text file with the user id provided by the facebook messenger
     to then read out that convo_id for further use and recognition 
- [ ] Let ARGUS accept german special characters (ä,ö,ü,ß)                   -> still to come.
- [x] Use AIML 2.0 for future AIML files (changed AIML-Template already)     -> Done.
- [ ] Remove information needed for database connection from Hermes to       -> still to come.
     write a file which contains those
- [ ] Connect do ARGUS via SSH to change some settings                       -> still to come.
- [ ] Let ARGUS be able to have a mood which is connected to his answers     -> still to come.
- [ ] ARGUS should answer different also depending on the relationship       -> still to come.
     to the user
- [ ] Create a new convo_id whenever another user writes to ARGUS            -> still to come.
     (integrate a user detection sytem)
- [x] Part ARGUS in different classes with different tasks                   -> Done.
- [ ] Let Hermes just be the message delivery system and all other tasks
     be handled by other classes
- [x] Write a handler for creating a logfile on encounting an error          -> Done.
- [ ] Change the way ARGUS creates conversation_ids                          -> still to come.
- [ ] Implement all the english aiml files for english conversations         -> still to come.
- [ ] Create multiple logfiles for logging different things (eg.: Messages
      and/or connections and so forth)                                       -> still to come.
- [ ] Put the whole cURL thing into a different class to slim the index.php  -> Done (outsourced to lib.php).


### Classes and functions
A brief overview of planned functions and classes
* [ ] Zeus.class.php for weather predictions via 
      OpenWeathermaps-API
* [ ] Hermes.class.php just for connection with the other APIs and 
      sending our constructed message
* [ ] Pythia.class.php for testing database querys inside classes
* [ ] Athene.class.php for testing the WolframAlpha-API and getting
      results for asked questions.
* [ ] Aiolos.class.php for querys regarding some of the Google-APIs
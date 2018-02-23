# A.R.G.U.S

## List of next features to implement:
As of 2018-02-12 (V 0.0.1.2):
- [] Add new aiml files to ARGUS                                            -> still to come.
- [] Complete greeting.aiml                                                 -> still to come.
- [] Prepare Hermes-class for use with OpenWeather-API                      -> still to come.
- [x] Minor changes to database structure                                   -> Done.
- [x] Minor performance raise                                               -> Done.

## List of things to do:
As of 2018-02-15 (V 0.0.1.3):
- [] Changes to greeting.aiml for every possible greeting                   -> half done.
- [] Create a ARGUS vocabulary for future conversations (maybe automatic?)  -> still to come.
- [] Change the method of recognition by saving the convo id                -> still to come.
     in an text file with the user id provided by the facebook messenger
     to then read out that convo_id for further use and recognition 
- [] Let ARGUS accept german special characters (ä,ö,ü,ß)                   -> still to come.
- [x] Use AIML 2.0 for future AIML files (changed AIML-Template already)    -> Done.
- [] Remove information needed for database connection from Hermes to       -> still to come.
     write a file which contains those
- [] Connect do ARGUS via SSH to change some settings                       -> still to come.
- [] Let ARGUS be able to have a mood which is connected to his answers     -> still to come.
- [] ARGUS should answer different also depending on the relationship       -> still to come.
     to the user
- [] Create a new convo_id whenever another user writes to ARGUS            -> still to come.
     (integrate a user detection sytem)
- [] Part ARGUS in different classes with different tasks                   -> still to come.
- [] Let Hermes just be the message delivery system and all other tasks
     be handled by other classes

### Classes and functions
A brief overview of planned functions and classes
* [] Ra.class.php or Zeus.class.php for weather predictions via 
     OpenWeathermaps-API
* [] Hermes.class.php just for connection with the other APIs and 
     sending our constructed message


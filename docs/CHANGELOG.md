# A.R.G.U.S

## Changelog
2018-02-12 - V 0.0.1.2
- Added greeting.aiml prototype
- Changed Hermes.class.php to be able to communicate with other apis
- Deleted test.aiml

2018-02-14 - V 0.0.1.3
- Minor changes to greeting.aiml
- Added possiblity to communicate with hermes-database
- Added name recognition of user

2018-02-14 - V 0.0.1.4
- Added method to recognize the user 
- Possiblity to greet the user with his name
- On beginning a conversation, write a file containing a constructed convo_id
- Removed convo_id from Hermes.class.php

2018-02-22 - V 0.0.1.4
- Changed AIML-Template to use aiml-standard 2.0
- Let ARGUS use german special chars (ä,ö,ü,ß)
- using first special tags from 2.0 standard (topic, that)##

2018-02-26 - V 0.0.1.4
- Removed all API-Keys and URLs from files
- Added config-folder with config files for key and urls

2018-02-27 - V 0.0.1.4
- Added Pythia.class.php for communicating with our database
- Added new function Specialchars() to Hermes to transform all specialchars
- Removed database query from index and moved it to Pythia

2018-03-02 - V 0.0.1.4
- Removed functions for creating and reading convo_id-file
- Added Zeus prototype
- Added Athene prototype
- Removed public function Specialchars from index.php
  to be called inside Hermes
- Removed database connection from index.php

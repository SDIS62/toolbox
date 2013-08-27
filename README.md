# SDIS62 : Toolbox

## Description

C'est la boite à outils pour les futurs développements du SDIS 62.

## Documentation

### SDIS62_Oauth_Consumer_Controller_Abstract

C'est une classe abstraire représentant un controller servant à attaquer une authentification oauth.

Utilisation : créer une classe étendant SDIS62_Oauth_Consumer_Controller_Abstract

### SDIS62_View_Helper_FlashMessenger

Une aide de vues pour interroger les messages du flashMessenger

* output($my_key = null, $template = '<li class="alert alert-%s" ><button data-dismiss="alert" class="close">&times;</button><strong class="alert-%s">%s</strong> %s</li>')
* getMessages()
* hasMessages()
* count()

### SDIS62_Layout_Controller_Plugin_Layout

Améliore le fonctionnement des vues, comme par exemple la récupération du bon layout en fonction du module.

### SDIS62_Service_CommandCenter

Service servant à interroger le command center.

* getUserAccount($id_user) : Get the user account
* verifyUserCredentials($email, $password) : Verify the user's credentials
* isUserAuthorized($id_user, $id_application) : Check if the user is authorized to access the app
* getUserApplications($id_user) : Get user's application (ACL)
* getApplication($id_application) : Get the application
* getApplicationByConsumerKey($consumer_key) : Get the application by the consumer_key

### SDIS62_Service_Module

Modules servant les différentes applications de l'écosystème. (Application en lecture / écriture).

#### SDIS62_Service_Module_Connect

* __construct(Zend_Oauth_Token_Access $accessToken, array $oauthOptions = array())
* getAccount() : récupère les informations utilisateur
* getApplications() : récupère les applications utilisateur
* getNavigation() : récupère la navigation user_nav

#### SDIS62_Service_Module_Notification

* send(array $recipientsid, $subject, $message, array $types = array("mail"))

#### SDIS62_Service_Module_Message

* send()

### SDIS62_Service_Bridge

Service servant à interroger des applications hors écosystème (lecture seule).

#### SDIS62_Service_Bridge_Start

* getListeInterventions($nbPage = 1, $nbInter = 20, $ville = null)
* getInter($numInter)
* getInterMainCourante($numInter)
* getInterChronologie($numInter)
* getInterCentresEngages($numInter)


Copyright (c) 2013 SDIS62

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
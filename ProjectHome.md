This project is a clone of an existing Google code project that was created on April of 2012th, with a misspelled name and due the Google code does not allow to change the name, this new one project has been created like workaround to correct the name; the original project has been deleted, because it contains the same content with this more detailed English description plus some information wiki pages.

This project includes three different parts around [Zend Framework](http://framework.zend.com/), that they are the next (if you would like to read this in Spanish go [here](DeccripcionDelProyecto.md)):

1. [Zend\_Application\_Resource](http://framework.zend.com/manual/en/zend.application.theory-of-operation.html#zend.application.theory-of-operation).

The project has some inherit classes of the [Zend\_Application\_Resource\_ResourceAbstract](http://framework.zend.com/manual/en/zend.application.core-functionality.html#zend.application.core-functionality.resource-resourceabstract)  abstract class provided by the _Zend framework_ and which are used to parametrize it using configuration files (.ini, .xml and so on) which are loaded in the application bootstrap (more information about this concept in the ZF documentation: http://framework.zend.com/manual/en/zend.application.theory-of-operation.html).

Theses classes provide the configuration of some ZF features that in the release 1.11 (This release was the release available when I started this development) does not supplied directly by the same framework.

2. Parametrization system of [Zend\_Controller\_Action](http://framework.zend.com/manual/en/zend.controller.action.html).
This is the most important part of this development. In this part I develop a parametrization system of the ZF actions ([Zend\_Controller\_Action](http://framework.zend.com/manual/en/zend.controller.action.html)) throwing configurations files defined in some storage data base (plain text files, relational database and so on). I also develop the class ifc\zend\resource\ModuleConfigurator, which inherit from [Zend\_Application\_Resource\_ResourceAbstract](http://framework.zend.com/manual/en/zend.application.core-functionality.html#zend.application.core-functionality.resource-resourceabstract), to parametrize this same system using the ZF application configuration  file ([application.ini](http://framework.zend.com/manual/en/learning.quickstart.create-project.html#learning.quickstart.create-project.configuration)) to define the plugins implementations to deal with the configurations files commented above.
Next there are some details about the main parts which compose this system:

  * The architecture of system provides the skeleton to get the configurations parameters from any source; however the project only contains one implementation which get the values of the parameters from .ini files (plain text files) located in some directory that need to be referenced from the module root directory whose action, to be executed on each request, belongs; this class is _ifc\zend\controller\action\helper\configurator\Standar_.

  * After the load of the values of the parameters of each action, the system, using a configurator, execute all the configurations defined for that action. The configurator is a some class that implements the interface _ifc\zend\controller\action\configurator\ConfiguratorInterface_, and his role is to manage and coordinate the load of the values of the parameters to apply to each action.
> The project supply an abstract class,  _ifc\zend\controller\action\configurator\ConfiguratorAbstract_, with the implementation of some methods to ease some regarded common functions share for the implementation of the future configurators;  _ifc\zend\controller\action\configurator\Basic_ is an inherit class of that abstract class and provide one way to define configurations in the next three levels:
    * Module: the configurations will be applied to all controllers of the module and to the actions of them.
    * Controller: the configurations will be applied to all the actions of the controller.
    * Action: the configurations will only be applied to the specified action.

> However, the system also allows to redefine the values applied on superior levels, to change these, on some controllers/actions which require that.

  * The configurator manages and coordinates configurations, which are objects from classes that implement the interface _ifc\zend\controller\action\configuration\ConfigurationInterface_, or  also   inheriting of the abstract class _ifc\zend\controller\action\configuration\ConfigurationAbstract_, that implements this interface and also provide the some implemented methods that were regarded of common use for several different configurations.
> The configurations store the parameter values, getting these from the some data base type and also execute the the parametrization operations into the action or in some components that it uses.
> This project provide some implemented configurations which allow to parametrize some required actions and components; all of these are defined into the _namespace_  _ifc\zend\controller\action\configuration_.

3. Modules for applications.
The project also contains the implementation of two modules that may be used in several web applications due which are of common use, however this modules are in alpha release and they do not provide all expected functionalities and moreover need more intensive tests. These modules are:

  * Authentication module (login).
> This module supply a login mechanism based in objects mapped to a relational data base throwing the [Doctrine ORM](http://www.doctrine-project.org/) (Object Relational Mapper) 2.0.1, for using any RDBMS (_relational data base management system_) that Doctrine supports.

> The module has implemented the login and logout functionalities with some security tweaks.

  * Exception controller module.
> The module catch the exceptions to register them in the configured system logs, into the _Zend Application_ bootstrap system, and send HTTP responses with the code and error message if debug mode is enabled, otherwise sending a general information message.

You can take a look to the software architecture design in this [wikipage](SoftwareArchitecture.md).
El proyecto incluye tres partes diferenciadas pero todas relacionados con  [Zend Framework](http://framework.zend.com/), que son la siguientes:

1. [Zend\_Application\_Resource](http://framework.zend.com/manual/en/zend.application.theory-of-operation.html#zend.application.theory-of-operation).

El proyecto contiene algunas clases que heredan de la clase abstracta [Zend\_Application\_Resource\_ResourceAbstract](http://framework.zend.com/manual/en/zend.application.core-functionality.html#zend.application.core-functionality.resource-resourceabstract) proporcionada por el mismo Zend framework y que se utilizan para poder establecer parametrizaciones del framework a través del fichero de configuración (.ini, .xml, …) que se carga en el _bootstrap_ de la aplicación (se puede obtener una referencia a este concepto en la documentación de ZF, concretamente en http://framework.zend.com/manual/en/zend.application.theory-of-operation.html).

Con estas clases he permitido parametrizar mediante esta funcionalidad ofrecida por ZF, algunos aspectos que no estaban implementados en la versión 1.11, que era la versión estable que había  en el momento que empecé el desarrollo de este proyecto.



2. 2. Sistema de parametrización de [Zend\_Controller\_Action](http://framework.zend.com/manual/en/zend.controller.action.html).

Esta es la parte principal de éste proyecto. En esta parte he desarrollado un sistema que permite parametrizar las acciones ([Zend\_Controller\_Action](http://framework.zend.com/manual/en/zend.controller.action.html)) a través de valores definidos en alguna base de datos (ficheros planos, base de datos relacionales, etc.);  el proyecto también tiene una clase, _ifc\zend\resource\ModuleConfigurator_,  que hereda de  _Zend\_Application\_Resource\_ResourceAbstract que permite parametrizar este sistema a través del fichero de configuración de la a aplicación ZF ([application.ini](http://framework.zend.com/manual/en/learning.quickstart.create-project.html#learning.quickstart.create-project.configuration)) permitiendo definir las implementaciones de los plugins que ejecutan las configuraciones._

A continuación se detalla las distintas partes que componen este sistema:



  * El sistema está preparado para poder obtener los valores de los parámetros de cualquier tipo base de datos, no obstante el proyecto solo tiene implementado una clase que permite obtener los parámetros de un fichero de configuración, del tipo .ini, almacenado en un directorio que puede ser localizado desde el directorio raíz del módulo al cual pertenece la acción que se está ejecutando en cada llamada; está clase es  _ifc\zend\controller\action\helper\configurator\Standar_.

  * Posteriormente a la carga de los valores de los parámetros de cada acción, el sistema ejecuta, a través de un configurador, la ejecución de todas las configuraciones correspondientes a la acción; el configurador, tiene que ser una clase que implemente la interfaz _ifc\zend\controller\action\configurator\ConfiguratorInterface_. 2. El configurador es el que gestiona y coordina la carga de las parametrizaciones asociadas a la acción que se ejecuta en cada llamada.

> El proyecto contiene la implementación de una clase abstracta, _ifc\zend\controller\action\configurator\ConfiguratorAbstract_, que contiene ciertas funciones que se han considerado que pueden ser globales a los distintos configuradores que se pueden necesitar implementar, y también la implementación de un configurador, _ifc\zend\controller\action\configurator\Basic_ es una clase que hereda de la esa clase abstracta y permite definir configuraciones en los siguientes niveles:

  * Módulo: se aplican las configuraciones a todos los controladores y acciones de cada controlador que están definidos en el módulo.
  * Controlador: se aplican las configuraciones a todas las acciones del controlador.
  * Acción: solo se aplican las configuraciones a la acción específicada

> No obstante, el sistema permite redefinir los valores de las configuraciones que se han definido en un nivel superior y así cambiar los valores de las configuración en algún controlador y/o acción concreto que lo requiera.

  * El configuración gestiona y coordina configuraciones, que son objectos de clases que implementan la interfaz _ifc\zend\controller\action\configuration\ConfigurationInterface_, o heredan de la clase abstracta _ifc\zend\controller\action\configuration\ConfigurationAbstract_, que implementa dicha interfaz, pero proporciona la implementación de ciertos métodos que se han considerado que pueden ser de uso común en la implementación de distintas configuraciones.

> Las configuraciones, son las que almacenan los valores de los parámetros, obteniendo estos de algún tipo de base de datos, y las que ejecutan las operaciones de parametrización, que se requieren en la acción o por alguno de los componentes que ésta utiliza.

> El proyecto proporciona ciertas configuraciones implementadas, para parametrizar ciertas acciones requeridas por las acciones y para ciertos componentes; todas ellas se encuentra definidas dentro del espacio de nombres _ifc\zend\controller\action\configuration_.

3. Modulos para aplicaciones.

El proyecto también contiene la implementación de dos módulos que pueden ser utilizados en multitud en varias aplicaciones web debido a la funcionalidad que implementan; no obstante los módulos se encuentran en una versión alpha por lo que no ofrecen todas las funcionalidades que deberían ofrecer, además de necesitar ser más ampliamente testeados. Los módulos son los siguientes:

  * Módulo de autenticación (acceso al sistema).

> El módulo ofrece un sistema de autenticación basado en objetos de clases mapeados sobre una base de datos relacional a través del ORM (Object Relational Mapper) [Doctrine](http://www.doctrine-project.org/) (versión 2.0.1), permitiendo utilizar cualquier sistema gestor de base de datos soportado por [Doctrine](http://www.doctrine-project.org/).
El módulo tiene implementado las funcionales de inicio de sesión (Log in) y cierre de sesión (Log out), garantizando algunos aspectos de seguridad.

  * Módulo de control de excepciones.

> El módulo permite capturar las excepciones para posteriormente registrar las en los registros (logs) del sistema, configurados en el bootstrap de la aplicción Zend, y posteriormente enviar una respuesta con el código HTTP de estado correspondiente y la página asociada al código de error con la información del error en el caso que se haya activado o no la opción de mostrar dicha información.

var app = angular.module('ABMmascota', ['ngAnimate','ui.router','angularFileUpload'])



.config(function($stateProvider, $urlRouterProvider) {
  $stateProvider

 


.state('menu', {
    views: {
      'principal': { templateUrl: 'template/menu.html',controller: 'controlMenu' },
      'menuSuperior': {templateUrl: 'template/menuSuperior.html'}
    }
    ,url:'/menu'
  })


    .state('grilla', {
    url: '/grilla',
    views: {
      'principal': { templateUrl: 'template/templateGrilla.html',controller: 'controlGrilla' },
      'menuSuperior': {templateUrl: 'template/menuSuperior.html'}
    }
  })

    .state('alta', {
    url: '/alta',
    views: {
      'principal': { templateUrl: 'template/templateMascota.html',controller: 'controlAlta' },
      'menuSuperior': {templateUrl: 'template/menuSuperior.html'}
    }

  
  })

      .state('modificar', {
    url: '/modificar/{id}?:nombre:edad:fecha:tipo:sexo:foto',
     views: {
      'principal': { templateUrl: 'template/templateMascota.html',controller: 'controlModificacion' },
      'menuSuperior': {templateUrl: 'template/menuSuperior.html'}
    }

  })



  // if none of the above states are matched, use this as the fallback
  $urlRouterProvider.otherwise('/menu');
});





app.controller('controlMenu', function($scope, $http) {
  $scope.DatoTest="**Menu**";
});



app.controller('controlAlta', function($scope, $http ,$state,FileUploader,cargadoDeFoto) {
  $scope.DatoTest="**alta**";

  $scope.uploader = new FileUploader({url: 'PHP/nexo.php'});
  $scope.uploader.queueLimit = 1;

//inicio las variables
  $scope.mascota={};
  $scope.mascota.nombre= "natalia" ;
  $scope.mascota.edad= 12 ;
  $scope.mascota.fecha= "01/01/2004" ;
  $scope.mascota.tipo= "perro" ;
  $scope.mascota.sexo= "macho" ;
  $scope.mascota.foto="pordefecto.png";
  
  cargadoDeFoto.CargarFoto($scope.mascota.foto,$scope.uploader);
 


  $scope.Guardar=function(){
  console.log($scope.uploader.queue);
  if($scope.uploader.queue[0].file.name!='pordefecto.png')
  {
    var nombreFoto = $scope.uploader.queue[0]._file.name;
    $scope.mascota.foto=nombreFoto;
  }
  $scope.uploader.uploadAll();
    console.log("mascota a guardar:");
    console.log($scope.mascota);
  }
   $scope.uploader.onSuccessItem=function(item, response, status, headers)
  {
    //alert($scope.mascota.foto);
      $http.post('PHP/nexo.php', { datos: {accion :"insertar",mascota:$scope.mascota}})
        .then(function(respuesta) {       
           //aca se ejetuca si retorno sin errores        
         console.log(respuesta.data);
         $state.go("grilla");

      },function errorCallback(response) {        
          //aca se ejecuta cuando hay errores
          console.log( response);           
        });
    console.info("Ya guardé el archivo.", item, response, status, headers);
  };








});


app.controller('controlGrilla', function($scope, $http,$location,$state) {
  	$scope.DatoTest="**grilla**";


$scope.guardar = function(mascota){

console.log( JSON.stringify(mascota));
  $state.go("modificar, {mascota:" + JSON.stringify(mascota)  + "}");
}

 
 	$http.get('PHP/nexo.php', { params: {accion :"traer"}})
 	.then(function(respuesta) {     	

      	 $scope.ListadoMascotas = respuesta.data.listado;
      	 console.log(respuesta.data);

    },function errorCallback(response) {
     		 $scope.ListadoMascotas= [];
     		console.log( response);     
 	 });

 	$scope.Borrar=function(mascota){
		console.log("borrar"+mascota);
    $http.post("PHP/nexo.php",{datos:{accion :"borrar",mascota:mascota}},{headers: {'Content-Type': 'application/x-www-form-urlencoded'}})
         .then(function(respuesta) {       
                 //aca se ejetuca si retorno sin errores        
                 console.log(respuesta.data);
                    $http.get('PHP/nexo.php', { params: {accion :"traer"}})
                    .then(function(respuesta) {       

                           $scope.ListadoMascotas = respuesta.data.listado;
                           console.log(respuesta.data);

                      },function errorCallback(response) {
                           $scope.ListadoMascotas= [];
                          console.log( response);
                          
                     });

          },function errorCallback(response) {        
              //aca se ejecuta cuando hay errores
              console.log( response);           
      });
 	}// $scope.Borrar






});//app.controller('controlGrilla',

app.controller('controlModificacion', function($scope, $http, $state, $stateParams, FileUploader)//, $routeParams, $location)
{
  $scope.mascota={};
  $scope.DatoTest="**Modificar**";
  $scope.uploader = new FileUploader({url: 'PHP/nexo.php'});
  $scope.uploader.queueLimit = 1;
  $scope.mascota.id=$stateParams.id;
  $scope.mascota.nombre=$stateParams.nombre;
  $scope.mascota.edad=$stateParams.edad;
  $scope.mascota.fecha=$stateParams.fecha;
  $scope.mascota.tipo=$stateParams.tipo;
  $scope.mascota.sexo=$stateParams.sexo;
  $scope.mascota.foto=$stateParams.foto;

  $scope.cargarfoto=function(nombrefoto){

      var direccion="fotos/"+nombrefoto;  
      $http.get(direccion,{responseType:"blob"})
        .then(function (respuesta){
            console.info("datos del cargar foto",respuesta);
            var mimetype=respuesta.data.type;
            var archivo=new File([respuesta.data],direccion,{type:mimetype});
            var dummy= new FileUploader.FileItem($scope.uploader,{});
            dummy._file=archivo;
            dummy.file={};
            dummy.file= new File([respuesta.data],nombrefoto,{type:mimetype});

              $scope.uploader.queue.push(dummy);
         });
  }
  $scope.cargarfoto($scope.mascota.foto);


  $scope.uploader.onSuccessItem=function(item, response, status, headers)
  {
    $http.post('PHP/nexo.php', { datos: {accion :"modificar",mascota:$scope.mascota}})
        .then(function(respuesta) 
        {
          //aca se ejetuca si retorno sin errores       
          console.log(respuesta.data);
          $state.go("grilla");
        },
        function errorCallback(response)
        {
          //aca se ejecuta cuando hay errores
          console.log( response);           
        });
    console.info("Ya guardé el archivo.", item, response, status, headers);
  };


  $scope.Guardar=function(mascota)
  {
    if($scope.uploader.queue[0].file.name!='pordefecto.png')
    {
      var nombreFoto = $scope.uploader.queue[0]._file.name;
      $scope.mascota.foto=nombreFoto;
    }
    $scope.uploader.uploadAll();
  }
});//app.controller('controlModificacion')

app.service('cargadoDeFoto',function($http,FileUploader){
    this.CargarFoto=function(nombrefoto,objetoUploader){
        var direccion="fotos/"+nombrefoto;  
      $http.get(direccion,{responseType:"blob"})
        .then(function (respuesta){
            console.info("datos del cargar foto",respuesta);
            var mimetype=respuesta.data.type;
            var archivo=new File([respuesta.data],direccion,{type:mimetype});
            var dummy= new FileUploader.FileItem(objetoUploader,{});
            dummy._file=archivo;
            dummy.file={};
            dummy.file= new File([respuesta.data],nombrefoto,{type:mimetype});

              objetoUploader.queue.push(dummy);
         });
    }

});//app.service('cargadoDeFoto',function($http,FileUploader){

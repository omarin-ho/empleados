<template>
    <div class="container">
        <div class="row justify-content-center" v-if="!loader">
            <div class="toast" role="alert" aria-live="polite" aria-atomic="true" data-delay="1000">
                <div role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-body">
                        {{mensaje.body}}
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card" v-if="btnEditar">
                    <div class="card-header">Agregar Empleados</div>
                    <div class="card-body">
                            <form @submit.prevent="guardar">
                                <div class="form-group">
                                    <label>Codigo</label>
                                    <input type ="text" v-model="empleado.codigo" placeholder="Codigo" class="form-control" required @keypress="validarLetra($event)"
                                    >
                                </div>
                                <div class="form-group">
                                    <label>Nombre</label>
                                    <input type ="text" v-model="empleado.nombre" placeholder="Nombre" class="form-control"required @keypress="validarLetra($event)">
                                </div>
                                <div class="form-group">
                                    <label>Tipo de cambio</label>
                                    <input readonly type ="text" v-model="empleado.tipoCambio" placeholder="Tipo de cambio" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Salario en dolares</label>
                                    <input type ="number"  min="0" v-model="empleado.salarioDolares" placeholder="Salario dolares" class="form-control" required @keypress="onlyNumber($event)" @keyup="calcularPesos()">
                                </div>
                                <div class="form-group">
                                    <label>Salario en pesos</label>
                                    <input type ="number" min="0" v-model="empleado.salarioPesos" placeholder="Salario pesos" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Direccion</label>
                                    <input type ="text" v-model="empleado.direccion" placeholder="Direccion" class="form-control"required @keypress="validarLetra($event)">
                                </div>
                                <div class="form-group">
                                    <label>Estado</label>
                                    <input type ="text" v-model="empleado.estado" placeholder="Estado" class="form-control"required @keypress="validarLetra($event)">
                                </div>
                                <div class="form-group">
                                    <label>Ciudad</label>
                                    <input type ="text" v-model="empleado.ciudad" placeholder="Ciudad" class="form-control"required @keypress="validarLetra($event)">
                                </div>
                                <div class="form-group">
                                    <label>Telefono</label>
                                    <input type ="text" v-model="empleado.telefono" placeholder="Telefono" class="form-control"required @keypress="validarLetra($event)">
                                </div>
                                <div class="form-group">
                                    <label>Correo</label>
                                    <input type ="email" v-model="empleado.correo" placeholder="Correo" class="form-control"required @keypress="validarLetra($event)">
                                </div>
                                <div class="form-group" align="center">
                                    <button class="btn btn-primary btn-group" type="submit">Guardar</button>
                                    <button class="btn btn-danger btn-group" @click="cancelarEdicion">Cancelar</button>
                                </div>
                            </form>
                            <input @change="cambiarValor" hidden id="valorDato" class="form-control" type="text" ref="myTestField">
                        </div>
                </div>
                <div class="card" v-if="!btnEditar">
                    <div class="card-header">
                        <div class="form-inline">
                            <label>Lista de Empleados</label>
                            <input id="busqueda" class="form-control" name="busqueda" v-model="filtro" placeholder="Buscar" @keyup="filtrarTabla">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-hover">
                                    <tr>
                                        <th scope="col">Codigo</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Salario en dolares</th>
                                        <th scope="col">Salario en pesos</th>
                                        <th scope="col">Correo</th>
                                        <th scope="col">Activo</th>
                                        <th scope="col"><button class="btn btn-success" @click="agregar">Agregar Empleado</button></th>
                                    </tr>
                                    <tr v-for="(item, index) in lstEmpleados" :key="index">
                                        <td>{{item.codigo}}</td>
                                        <td>{{item.nombre}}</td>
                                        <td>{{item.salarioDolares}}</td>
                                        <td>{{item.salarioPesos}}</td>
                                        <td>{{item.correo}}</td>
                                        <td>{{(item.activo == true)?'SI':'NO'}}</td>
                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" v-model="item.activo" class="custom-control-input" :id="index" @change="cambiarEstatus(item)">
                                                <label class="custom-control-label" :for="index" >{{(item.activo == true)?'Desactivar':'Activar'}}</label>
                                            </div>
                                            <button class="btn btn-sm btn-info" title="Detalle del empleado" @click="detalle(item)">Ver</button>
                                            <button class="btn btn-sm btn-warning" title="Editar empleado" @click="editar(item)">Editar</button>
                                            <button class="btn btn-sm btn-danger" title="Eliminar empleado" @click="eliminar(item)">Eliminar</button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Loader-->
        <div v-if="loader" div class="text-center">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="eliminar" tabindex="-1" role="dialog" aria-labelledby="eliminar" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Confirmar</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Desea Eliminar El registro
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" @click="confirmaEliminar">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Detalle-->
        <div class="modal fade" id="detalle" tabindex="-1" role="dialog" aria-labelledby="detalle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="Modaldetalle">Detalle de Empleado</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group">
                            <li class="list-group-item">
                                Codigo: {{empleado.codigo}}
                            </li>
                            <li class="list-group-item">
                                nombre: {{empleado.nombre}}
                            </li>
                            <li class="list-group-item">
                                Tipo de cambio: {{tipoCambio}}
                            </li>
                            <li class="list-group-item">
                                Sueldo Dolares: {{empleado.salarioDolares}}
                            </li>
                            <li class="list-group-item">
                                Sueldo Pesos: {{empleado.salarioPesos}}
                            </li>
                            <li class="list-group-item">
                                Direccion: {{empleado.direccion}}
                            </li>
                            <li class="list-group-item">
                                Estado: {{empleado.estado}}
                            </li>
                            <li class="list-group-item">
                                Ciudad: {{empleado.ciudad}}
                            </li>
                            <li class="list-group-item">
                                Telefono: {{empleado.telefono}}
                            </li>
                            <li class="list-group-item">
                                Correo: {{empleado.correo}}
                            </li>
                            <li class="list-group-item">
                                Activo: {{(empleado.activo == true)?'SI':'NO'}}
                            </li>
                            <div class="card">
                                Porcentaje: <input type ="number" v-model="porciento" placeholder="Porcieto de aumento mensual" class="form-control" @keypress="onlyNumber($event)">
                                <button class="btn badge-danger" @click="calcularProyeccion(empleado)">
                                    Calcular
                                </button>
                                <li class="list-group-item">
                                    Proyección salarial a 6 meses (%{{porciento}}) Pesos:$ {{ProyeccionPesos}}
                                </li>

                                <li class="list-group-item">
                                    Proyección salarial a 6 meses (%{{porciento}}) Dolares:$ {{ProyeccionDolares}}
                                </li>
                            </div>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "EmpleadoComponent",
        data(){
            return{
                ProyeccionPesos:0,
                ProyeccionDolares:0,
                porciento:0,
                tipoCambio:0.0,
                lstEmpleadosBK:[],
                filtro: null,
                loader:false,
                btnEditar: false,
                mensaje:{header:'', body:''},
                lstEmpleados:[],
                empleado:{
                    id:'',
                    codigo:'',
                    nombre:'',
                    salarioDolares:0.0,
                    salarioPesos:0.0,
                    direccion:'',
                    estado:'',
                    ciudad:'',
                    telefono:'',
                    correo:'',
                    activo:true,
                    eliminado:false,
                    tipoCambio:null
                }
            }
        },
        methods:{
            /**
             * funcion para buscar los elementos
             */
            buscar(){
                this.loader = true;
                axios.get('/empleados').then(res=>{
                    this.lstEmpleados = res.data;
                    this.lstEmpleadosBK = this.lstEmpleados;
                    this.loader = false;
                });
            },
            /**
             * Funcion para activar el agregar edicion
             */
            agregar(){
                this.empleado = {
                    id:'',
                    codigo:'',
                    nombre:'',
                    salarioDolares:'',
                    salarioPesos:'',
                    direccion:'',
                    estado:'',
                    ciudad:'',
                    telefono:'',
                    correo:'',
                    activo:true,
                    eliminado:false
                };
                this.btnEditar = true;
                this.consultarWebService();
            },
            /**
             * Funcion para guardar los datos
             */
            guardar(){
                if(this.empleado.codigo.trim() === '' || this.empleado.nombre.trim() === '' ||
                    this.empleado.direccion.trim() === '' || this.empleado.estado.trim() === '' ||
                    this.empleado.correo.trim() === '' || this.empleado.telefono.trim() === '' ||
                    this.empleado.ciudad.trim() === '' || this.empleado.salarioDolares.trim() === ''||
                    this.empleado.salarioPesos === ''){
                    alert('Debes completar todos los campos antes de guardar');
                    return;
                }
                this.loader = true;
                const params = this.empleado;
                console.log('DAtos enviados', this.empleado);
                axios.post('/empleados',params).then((res)=>{
                    if(res === null){
                        this.mostrarMensaje('Verifique sus datos', 500);
                    }
                    this.btnEditar = false;
                    this.loader = false;
                    this.buscar();
                    this.mostrarMensaje('Se guardo con Exito', 200);
                }).catch(()=>{
                    this.btnEditar = false;
                    this.loader = false;
                    this.mostrarMensaje('Error al guardar', 500);
                });
            },
            /**
             * Funcion para editar
             */
            editar(item){
                this.empleado = item;
                this.btnEditar = true;
                this.consultarWebService();
            },
            /**
             * funcion para abrir modal y preguntar si se requiere eliminar
             */
            eliminar(game){
                this.empleado.id = game.id;
                this.empleado.nombre = game.nombre;
                $('#eliminar').modal('show');
            },
            /**
             * Funcion que elimina el registro
             */
            confirmaEliminar(){
                axios.delete(`/empleados/${this.empleado.id}`).then(()=>{
                    $('#eliminar').modal('hide');
                    this.buscar();
                    this.mostrarMensaje('eliminado con Exito', 200);
                })
            },
            /**
             * Cancelar la edicion del formulario
             */
            cancelarEdicion(){
                this.btnEditar = false;
            },
            /**
             * Funcion para mostrar los mensajes
             * @param mensaje
             * @param tipo
             */
            mostrarMensaje(mensaje, tipo){
                switch (tipo) {
                    case 200:
                        this.mensaje.header='Exito';
                        break;
                    case 500:
                        this.mensaje.header='Exito';
                        break;
                }
                this.mensaje.body=mensaje;
                $('.toast').toast('show');
            },
            /**
             * Funcion para buscar mediante tipeado en una lista
             */
            filtrarTabla() {
                this.lstEmpleados = this.lstEmpleadosBK;
                this.lstEmpleados = this.lstEmpleadosBK.filter(element => {
                    return element.nombre.toLowerCase().indexOf(this.filtro.toString().toLowerCase()) > -1
                });
            },
            /**
             * Detalle del empleado
             * @param item
             */
            detalle(item){
                this.empleado = item;
                $('#detalle').modal('show');
            },
            /**
             * Cambiar el activo o inactivo
             * @param item
             */
            cambiarEstatus(item){
                const params = item;
                console.log(item);
                axios.post('/empleados/activar',params).then((res)=>{
                    this.loader = false;
                    this.mostrarMensaje('Se guardo con Exito', 200);
                });
            },
            /**
             * Consultamos el servicio REST
             **/
            consultarWebService(){
                $.ajax({
                    url : "http://www.banxico.org.mx/SieAPIRest/service/v1/series/SF43718/datos/2020-02-19/2020-02-19?token=0fce7d85c76c9afa6f8ef05f95d50a11c62573c637d28706e28f2a81e84264e5",
                    jsonp : "callback",
                    dataType : "jsonp", //Se utiliza JSONP para realizar la consulta cross-site
                    success : function(response) {  //Handler de la respuesta
                        console.log(response);
                        var salida = parseFloat(response.bmx.series[0].datos[0].dato);
                        $('#valorDato').val(salida);
                        $('#valorDato')[0].dispatchEvent(new Event('change'));

                    }
                });
            },
            /**
             * Calcular dolares a pesos
             */
            calcularPesos(){
                this.empleado.salarioPesos = parseInt(this.tipoCambio) * parseFloat(this.empleado.salarioDolares);
            },
            /**
             * funcion para Cambiar valor
             */
            cambiarValor(){
                this.tipoCambio = this.$refs.myTestField.value
                this.empleado.tipoCambio = this.tipoCambio;
            },
            /**
             * CAluclar Proyecion
             * @param empleado
             */
            calcularProyeccion(empleado){
                var totalPesos = 0;
                var totalDolares = 0;
                for(var i =0; i<= 6; i++){
                    totalPesos += parseFloat(empleado.salarioPesos) + (parseFloat(empleado.salarioPesos) * (parseFloat(this.porciento*100)));
                    totalDolares += parseFloat(empleado.salarioDolares) + (parseFloat(empleado.salarioDolares) * (parseFloat(this.porciento*100)));
                }
                this.ProyeccionPesos = totalPesos;
                this.ProyeccionDolares = totalDolares;
            },
            /**
             * Funcion para adminitir Solo numeros
             * @param evt
             * @returns {boolean}
             */
            onlyNumber ($event) {
                let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
                if ((keyCode < 48 || keyCode > 57) && keyCode !== 46) {
                    $event.preventDefault();
                }
            },
            /**
             * Funcion para validar caracteres especiales
             * @param $event
             */
            validarLetra: function ($event){
                console.log("Entre");
                if(($event.charCode >= 48 && $event.charCode <= 57) ||($event.charCode >= 65 && $event.charCode <= 90) ||($event.charCode >= 97 && $event.charCode <= 122)){
                    return true
                }else{
                    $event.preventDefault();
                }
            }
        },
        created() {
            $('.toast').toast({delay: 3000 });
            this.buscar();
        },
        computed:{
        }
    }
</script>

<style scoped>

</style>


<!--Canvas de los sensores y botonera -->
  <form action="" class="form90 border p-2  mx-1 " >
	  <fieldset>
			<div class=" row justify-content-between">
					<div class="col-8  mt-1">  <!-- "col-sm-8  mt-1"-->
							<canvas 
								id="tempe"
								data-type="radial-gauge"
								data-width="280"
								data-height="280"
								data-units="ºC"
								data-title="Temperatura"
								data-min-value="-6"
								data-max-value="6"
								data-major-ticks="[-6,-5,-4,-3,-2,-1,0,1,2,3,4,5,6]"
								data-minor-ticks="10"
								data-stroke-ticks="true"
								data-highlights='[
											{"from": -6, "to": 0, "color": "rgba(0,0, 255, 1)"},
											{"from": 0, "to": 6, "color": "rgba(255, 0, 0, 1)"}
										]'
								data-ticks-angle="265"
								data-start-angle="48"
								data-color-major-ticks="#ccc"
								data-color-minor-ticks="#eee"
								data-color-title="blue"
								data-color-units="red"
								data-color-numbers="blue"
								data-color-plate="#eee"
								data-border-shadow-width="3"
								data-borders="true"
								data-needle-type="arrow"
								data-needle-width="3"
								data-needle-circle-size="7"
								data-needle-circle-outer="true"
								data-needle-circle-inner="false"
								data-animation-duration="1500"
								data-animation-rule="linear"
								data-color-border-outer="#888"
								data-color-border-outer-end="#aaa"
								data-color-border-middle="#ddd"
								data-color-border-middle-end="#1F1"
								data-color-border-inner="#1F1"
								data-color-border-inner-end="#333"
								data-color-needle-shadow-down="#333"
								data-color-needle-circle-outer="gold"
								data-color-needle-circle-outer-end="#999"
								data-color-needle-circle-inner="#111"
								data-color-needle-circle-inner-end="#222"
								data-value-box-border-radius="4"
								data-color-value-box-rect="gold"
								data-color-value-box-rect-end="goldenrod"
								
								data-value-int="2"
								data-value-dec="2"
								
								fontNumbersSize= "50"
								fontTitleSize= "34"
								fontUnitsSize= "32"
								fontValueSize= "30"

								data-font-value="Verdana"
								data-font-numbers="Verdana"
								data-font-title="Verdana"
								data-font-units="Verdana"
								
								animatedValue= "true"
							></canvas>
							
					</div>  <!-- div class="col-sm-3" collumna gauge -->
				
		            <!-- Humedad -->
					<div class="col-auto mt-1">
                        <canvas data-type="linear-gauge"
                            id="hume"
                            data-width="160"
                            data-height="280"
                            data-border-radius="4"
                            data-borders="2"
                            data-bar-begin-circle="false"
                            data-title="Humedad"
                            data-color-title="blue"

                            data-units="%"
                            data-color-units="red"
                            fontunitssize="20"
							data-min-value="40"
							data-max-value="100"
                            data-minor-ticks="10"
                            data-value="92.3"
                            data-major-ticks="40,50,60,70,80,90,100"
                            data-highlights='[
											{"from": 40, "to": 50, "color": "rgba(255, 0, 0, .6)"},
											{"from": 50, "to": 75, "color": "rgba(204, 255, 0, .6)"},
											{"from": 75, "to": 100, "color": "rgba(0, 255, 0, .6)"}
										]'
                            data-tick-side="right"
                            data-number-side="right"
                            data-needle-side="right"
                            data-animation-rule="bounce"
                            data-animation-duration="1500"
                            data-bar-stroke-width="2"
                            data-value-text-shadow="false"
                            data-color-plate="#eee"
                            data-value-int="2"
                            data-value-dec="2"
                            data-color-bar-progress="rgba(0,200,0,.55)"
                            data-color-bar="rgba(227, 228, 229, .80)"
                            data-font-value="Verdana"
                            data-font-numbers="Verdana"
                            data-font-title="Verdana"
                            data-font-units="Verdana"
                            fontnumberssize="20"
                            fonttitlesize="34"
                            fontvaluesize="30"
                            data-value-box-border-radius="4"
                            data-color-value-box-rect="gold"
                            data-color-value-box-rect-end="goldenrod">

						</canvas>
					</div> <!-- class= col-sm-2   columna temperatura-->		
			</div> 

		    <div class="col-auto " style="background-color:BlanchedAlmond;">
			   <hr>
            </div>
	        <h2 class="col-auto mx-2" style="background-color:BlanchedAlmond;">Cambio de Valores</h2>
		    <div class="col-auto" style="background-color:BlanchedAlmond;">
			   <hr>
            </div>
		    <!--Cambio de Valores-->
			<div class="form-group-sm row justify-content-between">  <!--  -->
			    
				<div class="col-sm-8 mx-5">
					
					<label for="txttemperatura" id="lbltemperatura" class="col-auto col-form-label">Temperatura</label>
					<div class="col-sm-2">
						<input type="text" class="form-control-xs text-end" size="5" id="txttemperatura" name="txttemperatura" placeholder="Temp." style ="background-color:LightYellow;" data-bs-toggle="tooltip" title="Cambio de Temperatura">
					</div>
					
				</div> 
				<div class="col-auto mx-5"> <!--  col-sm-3 -->
					<label for="txthumedad" id="lblhumedad" class="col-auto col-form-label">Humedad</label>
					<div class="col-sm-2">
						<input type="text" class="form-control-xs text-end" size="5" id="txthumedad" name="txthumedad" placeholder="Humedad" style ="background-color:LightYellow;" data-bs-toggle="tooltip" title="Cambio de Humedad">
					</div>
					
				</div>
			</div>
		    <!--Notificaciones-->
		    <div class="form-group-sm row justify-content-between"> 
				<div class="col-sm-8 mx-5">
					<label  id="lbltemperaturaA" class="col-auto col-form-label" style="display:none;" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Estado de la programacion de la Temperatura">Actual</label> 
				</div> 
				<div class="col-auto mx-5">
					<label id="lblhumedadA" class="col-auto col-form-label" style="display:none;" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Estado de la Programcion de la Humedad">Actual</label>  
				</div> 
			</div>
		    <div id="passwordHelpBlock" class="form-text">
					<p>	Los valores admisibles para la Temperatua estan en el rango de -5 a +5 grados Celsius.<br>
						Los valores admisibles para la Humedad estan en el rango de 69% al 99%   
					</p>
			</div>
			<!--<br> -->

		    <!-- Botonera-->
		    <div class="col-auto" style="background-color:BlanchedAlmond;">
			   <hr>
            </div>
			<div class="form-group-md row  mx-2">
				<div class="col-sm-3">
					<button type="button" class="btn btn-info" onclick="changeValues(); "data-bs-toggle="tooltip" title="Asignar Nuevos Valores a los Sensores">
						<span class="glyphicon glyphicon glyphicon-edit"></span> Asignar Valores
					</button>
				</div>
				<div class="col-sm-3">
					<button type="button" class="btn btn-info" onclick="setUpValues();" data-bs-toggle="tooltip" title="Guardar los Valores Programados">
						<span class="glyphicon glyphicon-cloud-upload"></span> Guardar Programa
					</button>
				</div>
				<div class="col-sm-3">
					<button type="button" class="btn btn-info" onclick="refresValues();" data-bs-toggle="tooltip" title="Resfrescar valores">
						<span class="glyphicon glyphicon glyphicon-refresh"></span> Actualizar Valores
					</button>
				</div>
				<!--   ******  DEBUG *************** -->
				<!--<div class="col-sm-2">
					<button type="button" class="btn btn-info" onclick="prueba();" data-bs-toggle="tooltip" title="prueba funciones">
						<span class="glyphicon glyphicon glyphicon-refresh"></span> Prueba
					</button>
				</div>-->
			
			</div>
       </fieldset>
  </form>
  

   

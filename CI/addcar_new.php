<?php $this->load->view('common/headerfiles'); ?>
<?php $this->load->view('common/header'); 
$methodName = $this->router->method;

?>

<div class="main-panel" style="background-color: #f6f6f6;">
	<div class="dashboard-header">
		<div class="breadcrumb_c">
			Dashboard  <span>></span> Inventory upload & management <span>></span> <?php if($methodName == 'addcar') echo "Add"; else {echo "Edit";} ?> Vehicle
		</div>
	</div>

	<form name="addCar" id="addCar">
		<input type="hidden" name="hiddenCarId" id="hiddenCarId" value="<?= isset($carInfo->car_id)?$carInfo->car_id:'' ?>">
		<div class="edit-vehicle">
			<div class="edit-header">

				<!-- <div class="button-next-pre">
					<a class="btn btn-secondary " href="#" role="button"> <i class="fa fa-chevron-left" aria-hidden="true"></i></a>
					<a class="btn btn-secondary " href="#" role="button"> <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
				</div> -->

				<div class="d-flex justify-content-end align-items-center">
					<div class="mr-auto" ><h4><?php if($methodName == 'addcar') echo "Add"; else {echo "Edit";} ?> Vehicle</h4></div>
					<div class="p-2">
						<div class="button-groupe">
							<!-- <a class="btn" href="#" role="button"> <i class="fa fa-search" aria-hidden="true"></i> Window Sticker</a>
							<a class="btn" href="#" role="button"> <i class="fa fa-plus-circle" aria-hidden="true"></i> New Vehicle</a>
							<a class="btn" href="#" role="button"> <i class="fa fa-list-ul" aria-hidden="true"></i> List Vehicle</a> -->

							<a class="btn" href="#" role="button"> </a>
							<a class="btn" href="#" role="button"> </a>
							<a class="btn" href="#" role="button"> </a>

						</div>
					</div>
				</div>
			</div>

			<div class="edit-content">
				<div class="row">
					<div class="col-4">
						<div class="edit-imag-div" >
							<!-- <img src="https://images.cardekho.com/images/mycar/large/tata/tiago/marketing/Tata-Tiago.webp"> -->
						</div>

						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<label for="carLogo" class="form-control-label" >Logo</label>
									<input type="file" class="form-control" id="carLogo" name="carLogo" style="display: none;">
									
									<div class="images-div" id="logoDiv" >
										<?php
										if(isset($primaryImage))
										{
											$img=base_url().$primaryImage->image_name;
											?>
											<div class="img-box img-box-big logoCl" style="width:120px;height:120px;background-image: url('<?= $img ?>')"> </div>
											<?php
										}
										else
										{
											$img= base_url()."/uploads/cars/Default.jpg";
											?>
											<div class="img-box img-box-big" style="width:477px;height:350px;background-image: url('<?= $img ?>')"> </div>
											<?php
										}
										?>
									</div>
									<div id="carLogoErr"></div><br>
									<button type="button" class="btn btn-primary" onclick="$('#carLogo').click();">Upload Logo</button>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="form-group">
									<label for="carGallery" class="form-control-label">Gallery Images</label>
									<input type="file" class="form-control" id="carGallery" name="carGallery[]" multiple="" style="display: none;">
									
									<div class="images-div" id ="galleryDiv">
										<?php
										if(isset($carImages))
										{
											$counter =1;
											foreach($carImages as $row)
											{
												$img=base_url().$row['image_name'];
												?>
												<div class="img-box galleryImg" id="GI<?= $counter ?>" style="background-image: url('<?= $img; ?>')">
													<button type="button" class="delete_button" onclick="confirm('Are you sure your want to delete the image ?') &&  updateImage(<?= $row['image_id'];?>,<?= $counter?>)">
														<i class="fa fa-trash-o" aria-hidden="true"></i>
													</button>
												</div>
												<?php
												$counter++;
											}
										}

										$SessImages=$this->session->carGalleryImages;
										if(count($SessImages)>0)
										{
											foreach ($SessImages as $row) {
												$img=base_url('uploads/cars/').$row;
												?>
												<div class="img-box galleryImg" id="GI" style="background-image: url('<?= $img; ?>')">
													<button type="button" class="delete_button" onclick="confirm('Are you sure your want to delete the image ?') && deleteGalleryImages('<?= $row; ?>')">
														<i class="fa fa-trash-o" aria-hidden="true"></i>
													</button>
												</div>
												<?php
											}
										}


										?>
									</div>
									<div id="carGalleryErr"></div>
									<button type="button" class="btn btn-primary" onclick="$('#carGallery').click();">Upload Gallery Images</button>
								</div>
							</div>

						</div>
					</div>
					<div class="col-8">

						<div class="card card_form">
							<div class="form-title">
								<p><i class="fa fa-file-text-o" aria-hidden="true"></i> Listing details </p>
							</div>

							<div class="row">
								<div class="col-sm-12">
									<div class="form-group">
										<label for="carTitle" class="form-control-label">Title</label>
										<input type="text" class="form-control" id="carTitle" name="carTitle" value="<?= isset($carInfo->title)?$carInfo->title:'' ?>">
									</div>
								</div>
							</div>


							<div class="form-group">
								<label for="carDesc" class="form-control-label">Description</label>
								<textarea class="form-control" rows="5" id="carDesc" name="carDesc" style="min-height: 120px;"><?= isset($carInfo->description)?$carInfo->description:'' ?></textarea>
							</div>


							<div class="form-title">
								<p><i class="fa fa-phone" aria-hidden="true"></i> Contact information </p>
							</div>
							<div class="card card_form">

								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label for="carEmail" class="form-control-label">Email Address</label>
											<input type="email" class="form-control" id="carEmail" name="carEmail" value="<?= isset($carInfo->email)?$carInfo->email:'' ?>">
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label for="carPhone" class="form-control-label">Phone Number <small>(optional)</small></label>
											<input type="number" class="form-control" id="carPhone" name="carPhone" value="<?= isset($carInfo->phone)?$carInfo->phone:'' ?>">
										</div>
									</div>
								</div>
							</div>

							<div class="form-title">
								<p><i class="fa fa-map-marker" aria-hidden="true"></i> Location details</p>
							</div>

							<div class="card card_form">


								<div class="row">

									<div class="col-sm-4">
										<div class="form-group">
											<label for="carState" class="form-control-label">State </label>
											<input type="text" class="form-control" id="carState" name="carState" value="<?= isset($carInfo->state)?$carInfo->state:'' ?>">
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<label for="carCity" class="form-control-label">City </label>
											<input type="text" class="form-control" id="carCity" name="carCity" value="<?= isset($carInfo->city)?$carInfo->city:'' ?>">
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<label for="carZip" class="form-control-label">Zip Code</label>
											<input type="text" class="form-control" id="carZip" name="carZip" value="<?= isset($carInfo->zip)?$carInfo->zip:'' ?>">
										</div>
									</div>
								</div>

								<div class="form-group">
												<label for="carLocation" class="form-control-label">Location</label>

												<?php 
												if(isset($carInfo->location))
													$decodedLocation = json_decode($carInfo->location);
												?>

												<div class="map-div" >
													<div class="md-checkbox">
														<input id="job_location__custom_coords" type="checkbox" name="job_location__custom_coords" value="yes">
														<label for="job_location__custom_coords" class="">Pick coordinates</label>
													</div>
													<div>
														<input class="form-control" name="carLocation" id="geocomplete" type="text" placeholder="Type in an address" value="<?= isset($decodedLocation->location)?$decodedLocation->location:'' ?>" />
														<br><br>
														<fieldset>
															<!-- <label>Latitude</label> -->
															<input name="lat" type="hidden" value="<?= isset($decodedLocation->lat)?$decodedLocation->lat:'' ?>" id="latitudeInput" readonly="">

															<!-- <label>Longitude</label> -->
															<input name="lng" type="hidden" value="<?= isset($decodedLocation->long)?$decodedLocation->long:'' ?>" readonly="">

														</fieldset>

														<a id="reset" href="#" style="display:none;">Reset Marker</a>
													</div>

													<div class="map-box">
														<div class="map_canvas" style="height: 500px;width: auto"></div>
													</div>
												</div>
											</div> 

							</div>

							<div class="form-title">
								<p><i class="fa fa-map-marker" aria-hidden="true"></i> Basic details</p>
							</div>

							<div class="row">
								<div class="col-sm-10">

									<div class="form-group">
										<label for="carVinNUmber" class="form-control-label">VIN Number (optional)</label>
										<input type="text" class="form-control" id="carVinNUmber" name="carVinNUmber" value="<?= isset($carInfo->vin_number)?$carInfo->vin_number:''  ?>" >
									</div>

								</div>
								<div class="col-sm-2">
									<div class="form-group">
										<button type="button" style="margin-top: 30px;" class="btn btn-primary" id="vinSearch">Search</button>
									</div>
								</div>
							</div>


							<div class="row">
								<div class="col-sm-3">
									<label class="form-control-label">Car Brand</label>
									<?php
									$brandArr=array("Acura","Alfa Romeo","AM General","AMC","Aston Martin","Audi","Austin-Healey","Bentley","BMW","Bugatti","Buick","Cadillac","Cavalier","Chevrolet","Chrysler","Daewoo","Daihatsu","Datsun","De Tomaso","DeLorean","Desoto","Dodge","Eagle","Ferrari","FIAT","Fisker","Ford","Genesis","GEO","GMC","Honda","HUMMER","Hyundai","Infiniti","Isuzu","Jaguar","Jeep","Kia","Lamborghini","Land Rover","Lexus","Lincoln","Lotus","Maserati","Maybach","Mazda","McLaren","Mercedes-Benz","Mercury","MG","MINI","Mitsubishi","Morgan","Nash","Nissan","Oldsmobile","Opel","Packard","Plymouth","Pontiac","Porsche","RAM","Rolls-Royce","Saab","Saturn","Scion","Shelby","Smart","Sprinter","Studebaker","Subaru","Suzuki","Tesla","Toyota","Triumph","Volkswagen","Volvo","Willys");

									?>
									<select class="js-example-basic-single form-control selectComboboxCheck" name="carBrand" id="carBrand">
										<option value="0">Select a Brand</option>
										<?php
										foreach($brandArr as $row)
										{
											?>
											<option <?= isset($carInfo->brand) && $carInfo->brand== $row ? "selected":'' ?> value="<?= $row ?>"><?= $row ?></option>
											<?php
										}
										?>
									</select>

								</div>

								<div class="col-sm-3">
									<div class="form-group">
										<label for=carSeries" class="form-control-label" >Series</label>
										<input type="text" class="form-control selectComboboxCheck" id="carSeries" name="carSeries" value="<?= isset($carInfo->series)?$carInfo->series:'' ?>">
									</div>
								</div>

								<div class="col-sm-3">
									<div class="form-group row">
										<div class="col-12">
											<label class="form-control-label">Type</label>
											<?php
											$categoryArr=array("Compact","Convertible","Coupe","Crossover","Off-Road","SUV","Sedan","Sports car","Truck","Van","Wagon");
											?>
											<select class="js-example-basic-single form-control selectComboboxCheck" name="carCategory" id="carCategory">
												<option value="0">Select a Category</option>
												<?php
												if(isset($carInfo->category))
												{
													?>
													<option <?= isset($carInfo->category)? "selected":'' ?> value="<?=$carInfo->category ?>"><?= $carInfo->category ?></option>
													<?php
												}
												else
												{
													foreach ($categoryArr as $row) {
														?>
														<option <?= isset($carInfo->category) && $carInfo->category== $row ? "selected":'' ?> value="<?= $row ?>"><?= $row ?></option>
														<?php
													}
												}


												?>
											</select>
											<div id="ccErr" style="color:red"></div>
										</div>
									</div>
								</div>

								<div class="col-sm-3">
									<div class="form-group row">
										<div class="col-12">
											<label class="form-control-label">Primary Fuel </label>
											<select class="js-example-basic-single form-control selectComboboxCheck" name="carFuel" id="carFuel">
												<option value="0">Select a Category</option>
												<option <?= isset($carInfo->fuel) && $carInfo->fuel=="Diesel" ? "selected":'' ?> value="Diesel">Diesel</option>
												<option <?= isset($carInfo->fuel) && $carInfo->fuel=="Gasoline" ? "selected":'' ?> value="Gasoline">Gasoline</option>
												<option <?= isset($carInfo->fuel) && $carInfo->fuel=="Electric" ? "selected":'' ?> value="Electric">Electric</option>
												<option <?= isset($carInfo->fuel) && $carInfo->fuel=="Natural Gas" ? "selected":'' ?> value="Natural Gas">Natural Gas</option>
											</select>
										</div>
									</div>
								</div>

								

								<!-- <div class="col-sm-3">
									<div class="form-group row">
										<div class="col-12">
											<label class="form-control-label">Status</label>

											<select class="js-example-basic-single form-control selectComboboxCheck" name="carBrand" id="status">
												<option value="0">Active</option>

												<option>Inactive</option>

											</select>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="form-group row">
										<div class="col-12">
											<label class="form-control-label">Visibility</label>

											<select class="js-example-basic-single form-control selectComboboxCheck" name="carBrand" id="visibility">
												<option value="0">Public</option>

												<option>Private</option>

											</select>
										</div>
									</div>
								</div> -->
							</div>


							<div class="row">
								
								<div class="col-sm-3">
									<div class="form-group">
										<label for=carMileage" class="form-control-label">Odometer/ Mileage</label>
										<input type="number" class="form-control selectComboboxCheck" id="carMileage" name="carMileage" value="<?= isset($carInfo->mileage)?$carInfo->mileage:'' ?>">
									</div>
								</div>

								<div class="col-sm-3">
									<div class="form-group row">
										<div class="col-12">
											<label class="form-control-label">Manufacturing Year</label>
											<select class="js-example-basic-single form-control selectComboboxCheck" name="carManufacturingYear" id="carManufacturingYear">
												<option value="0">Select Year</option>
												<?php 
												$currentYear=date("Y");
												$uptoYear=1990;
												for($i=$currentYear; $i>=$uptoYear; $i--)
												{
													?>
													<option <?= isset($carInfo->manufacturing_year) && $carInfo->manufacturing_year == $i ? "selected":'' ?> value="<?= $i; ?>"><?= $i;?></option>
													<?php
												}
												?>	
											</select>
										</div>
									</div>
								</div>

								<div class="col-sm-3">
									<div class="form-group row">
										<div class="col-12">
											<label class="form-control-label">Exterior Colour (optional)</label>
											<?php
											$colorArr=array("Beige","Black","Blue","Brown","Burgundy","Charcoal","Cream","Dark Blue","Dark Green","Gold","Green","Grey","Light Blue","Light Green","Maroon","Orange","Pink","Purple","Red","Silver","Tan","Teal","White","Yellow","Camouflage");
											?>
											<select class="js-example-basic-single form-control" name="carColor" id="carColor">
												<option value="0">Select a Category</option>
												<?php 
												foreach ($colorArr as $row) {
													?>
													<option <?= isset($carInfo->color) && $carInfo->color == $row ? "selected":'' ?> value="<?= $row ?>"><?= $row ?></option>
													<?php
												}
												?>
											</select>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="form-group">
										<label for=carInteriorColor" class="form-control-label">Interior Colour</label>
										<input type="text" class="form-control selectComboboxCheck" id="carInteriorColor" name="carInteriorColor" value="<?= isset($carInfo->interior_color)?$carInfo->interior_color:'' ?>">
									</div>
								</div>

								

							</div>


							<div class="row">
								
								<div class="col-sm-3">
									<div class="form-group">
										<label for="carWeight" class="form-control-label">Car Weight (optional)</label>
										<input type="text" class="form-control" id="carWeight" name="carWeight" value="<?= isset($carInfo->weight)?$carInfo->weight:'' ?>">
									</div>

								</div>
								<div class="col-sm-3">
									<div class="form-group">
										<label for="carFuelUsage" class="form-control-label">Fuel usage per 100km (optional)</label>
										<input type="text" class="form-control" id="carFuelUsage" name="carFuelUsage" value="<?= isset($carInfo->fuel_usage)?$carInfo->fuel_usage:'' ?>">
									</div>
								</div>
								<div class="col-sm-3">
									<div class="form-group">
										<label for="carMaxPassengers" class="form-control-label">Max Passengers (optional)</label>
										<input type="number" class="form-control" id="carMaxPassengers" name="carMaxPassengers" value="<?= isset($carInfo->max_passengers)?$carInfo->max_passengers:'' ?>">
									</div>
								</div>
								<div class="col-sm-3">
									<div class="form-group">
										<label for="carNoOfDoors" class="form-control-label">Number of doors (optional)</label>
										<input type="number" class="form-control" id="carNoOfDoors" name="carNoOfDoors" value="<?= isset($carInfo->no_of_doors)?$carInfo->no_of_doors:'' ?>">
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-sm-3">
									<div class="form-group row">
										<div class="col-12">
											<label class="form-control-label">Transmission (optional)</label>
											<select class="js-example-basic-single form-control" name="carTransmission" id="carTransmission">
												<option value="0">Select a Category</option>
												<option <?= isset($carInfo->transmission) && $carInfo->transmission == "Automatic" ? "selected":'' ?> value="Automatic">Automatic</option>
												<option <?= isset($carInfo->transmission) && $carInfo->transmission == "Manual" ? "selected":'' ?> value="Manual">Manual</option>
												<option <?= isset($carInfo->transmission) && $carInfo->transmission == "Semi-automatic" ? "selected":'' ?> value="Semi-automatic">Semi-automatic</option>
											</select>
										</div>
									</div>
								</div>
								
								<div class="col-sm-3">
									<div class="form-group row">
										<div class="col-12">
											<label class="form-control-label">Drivetrains (optional)</label>
											<select class="js-example-basic-single form-control" name="carDrivetrains" id="carDrivetrains">
												<option value="0">Select a Category</option>
												<option <?= isset($carInfo->drivetrains) && $carInfo->drivetrains == "AllWheelDrive" ? "selected":'' ?> value="AllWheelDrive">All Wheel Drive</option>
												<option <?= isset($carInfo->drivetrains) && $carInfo->drivetrains == "FrontWheelDrive" ? "selected":'' ?> value="FrontWheelDrive">Front Wheel Drive</option>
												<option <?= isset($carInfo->drivetrains) && $carInfo->drivetrains == "RearWheelDrive" ? "selected":'' ?> value="RearWheelDrive">Rear Wheel Drive</option>
												<option <?= isset($carInfo->drivetrains) && $carInfo->drivetrains == "Four Wheel Drive" ? "selected":'' ?> value="Four Wheel Drive">Four Wheel Drive</option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="form-group row">
										<div class="col-12">
											<label class="form-control-label">Status (optional)</label>
											<select class="js-example-basic-single form-control" name="carStatus" id="carStatus">
												<option value="0">Select a Category</option>
												<option <?= isset($carInfo->status) && $carInfo->status == "0" ? "selected":'' ?> value="0">Available</option>
												<option <?= isset($carInfo->status) && $carInfo->status == "1" ? "selected":'' ?> value="1">Not Available</option>
											</select>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="form-group row">
										<div class="col-12">
											<label class="form-control-label">Engine (optional)</label>
											<select class="js-example-basic-single form-control" name="carEngine" id="carEngine">
												<option value="0">Select a Category</option>
												<option <?= isset($carInfo->engine) && $carInfo->engine == "Twostrokeengine" ? "selected":'' ?> value="Twostrokeengine">Two stroke engine</option>
												<option <?= isset($carInfo->engine) && $carInfo->engine == "Fourstrokeengine" ? "selected":'' ?> value="Fourstrokeengine">Four stroke engine</option>
												<option <?= isset($carInfo->engine) && $carInfo->engine == "Sixstrokeengine" ? "selected":'' ?> value="Sixstrokeengine">Six stroke engine</option>
												<option <?= isset($carInfo->engine) && $carInfo->engine == "ReciprocatingEngine" ? "selected":'' ?> value="ReciprocatingEngine">Reciprocating Engine</option>
												<option <?= isset($carInfo->engine) && $carInfo->engine == "WankelEngine" ? "selected":'' ?> value="WankelEngine">Wankel Engine</option>
												<option <?= isset($carInfo->engine) && $carInfo->engine == "Compressionignitionengine" ? "selected":'' ?> value="Compressionignitionengine">Compression ignition engine</option>
												<option <?= isset($carInfo->engine) && $carInfo->engine == "Spark-ignitionengine" ? "selected":'' ?> value="Spark-ignitionengine">Spark-ignition engine</option>
												<option <?= isset($carInfo->engine) && $carInfo->engine == "ElectricMotor" ? "selected":'' ?> value="ElectricMotor">Electric Motor</option>
												<option <?= isset($carInfo->engine) && $carInfo->engine == "HCCI" ? "selected":'' ?> value="HCCI">HCCI</option>
												<option <?= isset($carInfo->engine) && $carInfo->engine == "Singlecylinderengine" ? "selected":'' ?> value="Singlecylinderengine">Single cylinder engine</option>
												<option <?= isset($carInfo->engine) && $carInfo->engine == "Multiplecylinderengine" ? "selected":'' ?> value="Multiplecylinderengine">Multiple cylinder engine</option>
												<option <?= isset($carInfo->engine) && $carInfo->engine == "In-lineengine" ? "selected":'' ?> value="In-lineengine">In-line engine</option>
												<option <?= isset($carInfo->engine) && $carInfo->engine == "Vengine" ? "selected":'' ?> value="Vengine">V engine</option>
												<option <?= isset($carInfo->engine) && $carInfo->engine == "WEngine" ? "selected":'' ?> value="WEngine">W Engine</option>
												<option <?= isset($carInfo->engine) && $carInfo->engine == "OPOCengine" ? "selected":'' ?> value="OPOCengine">OPOC engine</option>
												<option <?= isset($carInfo->engine) && $carInfo->engine == "Naturallyaspirated" ? "selected":'' ?> value="Naturallyaspirated">Naturally aspirated</option>
												<option <?= isset($carInfo->engine) && $carInfo->engine == "SuperchargedandTurbocharged" ? "selected":'' ?> value="SuperchargedandTurbocharged Engine">Supercharged and Turbocharged Engine</option>
												
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-3">
									<div class="form-group">
										<label for="carDisplacement" class="form-control-label">Displacement - ccm (optional)</label>
										<input type="text" class="form-control" id="carDisplacement" name="carDisplacement" value="<?= isset($carInfo->displacement)?$carInfo->displacement:'' ?>">
									</div>
								</div>
							</div>
							
						</div>



						<div class="card card_form">
							<div class="form-title">
								<p><i class="fa fa-dollar" aria-hidden="true"></i> Pricing </p>
							</div>


							<div class="row">
								<div class="col-sm-12">
									<div class="form-group">
										<label for="carPrice" class="form-control-label">Price</label>
										<input type="number" class="form-control" id="carPrice" name="carPrice" value="<?= isset($carInfo->price)?$carInfo->price:'' ?>">
									</div>

								</div>
								<div class="col-sm-6">


								</div>
							</div>

						</div>

						<div class="card card_form" id="tagsDiv">
							<div class="form-title">
								<p><i class="fa fa-map-marker" aria-hidden="true"></i> Tags <small>(Optional)</small> </p>
							</div>
							<?php 
							if(isset($carInfo->tags))
							{
								$tagsDecoded=json_decode($carInfo->tags);
								if(isset($tagsDecoded))
								{
									$index = array_search("Air conditioner",$tagsDecoded);
									if($index !== FALSE)
										$ACchecked="checked";
									else
										$ACchecked="";
								}
							}
							?>

							<div class="row">
								<div class="col-sm-6 col-md-3">
									<div class="form-group">
										<div class="md-checkbox">
											<input id="tagAC" type="checkbox" name="tag[]" value="Air conditioner" <?= isset($ACchecked)?$ACchecked:'' ?>>
											<label for="tagAC" class="">Air conditioner</label>
										</div>
									</div>
								</div>
								<div class="col-sm-6 col-md-3">
									<?php
									if(isset($tagsDecoded))
									{
										$index = array_search("Air Bags",$tagsDecoded);
										if($index !== FALSE)
											$ABchecked="checked";
										else
											$ABchecked="";
									}

									?>
									<div class="form-group">
										<div class="md-checkbox">
											<input id="tagAB" type="checkbox" name="tag[]" value="Air Bags" <?= isset($ABchecked)?$ABchecked:'' ?>>
											<label for="tagAB" class="">Air Bags</label>
										</div>
									</div>
								</div>
								<div class="col-sm-6 col-md-3">
									<?php
									if(isset($tagsDecoded))
									{
										$index = array_search("Electric Windows",$tagsDecoded);
										if($index !== FALSE)
											$EWchecked="checked";
										else
											$EWchecked="";
									}
									?>
									<div class="form-group">
										<div class="md-checkbox">
											<input id="tagEW" type="checkbox" name="tag[]" value="Electric Windows" <?= isset($EWchecked)?$EWchecked:'' ?>>
											<label for="tagEW" class="">Electric Windows</label>
										</div>
									</div>
								</div>
								<div class="col-sm-6 col-md-3">
									<?php
									if(isset($tagsDecoded))
									{
										$index = array_search("Central Locking",$tagsDecoded);
										if($index !== FALSE)
											$CLchecked="checked";
										else
											$CLchecked="";
									}
									?>
									<div class="form-group">
										<div class="md-checkbox">
											<input id="tagCL" type="checkbox" name="tag[]" value="Central Locking" <?= isset($CLchecked)?$CLchecked:'' ?>>
											<label for="tagCL" class="">Central Locking</label>
										</div>
									</div>
								</div>

							</div>

							<div class="row">
								<div class="col-sm-6 col-md-3">
									<?php
									if(isset($tagsDecoded))
									{
										$index = array_search("Child Seat",$tagsDecoded);
										if($index !== FALSE)
											$CSchecked="checked";
										else
											$CSchecked="";
									}
									?>
									<div class="form-group">
										<div class="md-checkbox">
											<input id="tagCS" type="checkbox" name="tag[]" value="Child Seat" <?= isset($CSchecked)?$CSchecked:'' ?>>
											<label for="tagCS" class="">Child Seat</label>
										</div>
									</div>
								</div>
								<div class="col-sm-6 col-md-3">
									<?php
									if(isset($tagsDecoded))
									{
										$index = array_search("USB Port",$tagsDecoded);
										if($index !== FALSE)
											$USBchecked="checked";
										else
											$USBchecked="";
									}
									?>
									<div class="form-group">
										<div class="md-checkbox">
											<input id="tagUP" type="checkbox" name="tag[]" value="USB Port" <?= isset($USBchecked)?$USBchecked:'' ?>>
											<label for="tagUP" class="">USB Port</label>
										</div>
									</div>
								</div>
								<div class="col-sm-6 col-md-3">
									<?php
									if(isset($tagsDecoded))
									{
										$index = array_search("Heated Seats",$tagsDecoded);
										if($index !== FALSE)
											$HSchecked="checked";
										else
											$HSchecked="";
									}
									?>
									<div class="form-group">
										<div class="md-checkbox">
											<input id="tagHS" type="checkbox" name="tag[]" value="Heated Seats" <?= isset($HSchecked)?$HSchecked:'' ?>>
											<label for="tagHS" class="">Heated Seats</label>
										</div>
									</div>
								</div>
								<div class="col-sm-6 col-md-3">
									<?php
									if(isset($tagsDecoded))
									{
										$index = array_search("Keyless entry",$tagsDecoded);
										if($index !== FALSE)
											$KEchecked="checked";
										else
											$KEchecked="";
									}
									?>
									<div class="form-group">
										<div class="md-checkbox">
											<input id="tagKE" type="checkbox" name="tag[]" value="Keyless entry" <?= isset($KEchecked)?$KEchecked:'' ?>>
											<label for="tagKE" class="">Keyless entry</label>
										</div>
									</div>
								</div>

							</div>

							<div class="row">
								<div class="col-sm-6 col-md-3">
									<?php
									if(isset($tagsDecoded))
									{
										$index = array_search("Audio System",$tagsDecoded);
										if($index !== FALSE)
											$ASchecked="checked";
										else
											$ASchecked="";
									}
									?>
									<div class="form-group">
										<div class="md-checkbox">
											<input id="tagAS" type="checkbox" name="tag[]" value="Audio System" <?= isset($ASchecked)?$ASchecked:'' ?>>
											<label for="tagAS" class="">Audio System</label>
										</div>
									</div>
								</div>
								<div class="col-sm-6 col-md-3">
									<?php
									if(isset($tagsDecoded))
									{
										$index = array_search("Sunroof",$tagsDecoded);
										if($index !== FALSE)
											$SUNchecked="checked";
										else
											$SUNchecked="";
									}
									?>
									<div class="form-group">
										<div class="md-checkbox">
											<input id="tagSunroof" type="checkbox" name="tag[]" value="Sunroof" <?= isset($SUNchecked)?$SUNchecked:'' ?>>
											<label for="tagSunroof" class="">Sunroof</label>
										</div>
									</div>
								</div>
								<div class="col-sm-6 col-md-3">
									<?php
									if(isset($tagsDecoded))
									{
										$index = array_search("Parking Camera System",$tagsDecoded);
										if($index !== FALSE)
											$PCSchecked="checked";
										else
											$PCSchecked="";
									}
									?>
									<div class="form-group">
										<div class="md-checkbox">
											<input id="tagPCS" type="checkbox" name="tag[]" value="Parking Camera System" <?= isset($PCSchecked)?$PCSchecked:'' ?>>
											<label for="tagPCS" class="">Parking Camera System</label>
										</div>
									</div>
								</div>
								<div class="col-sm-6 col-md-3">
									<?php
									if(isset($tagsDecoded))
									{
										$index = array_search("GPS",$tagsDecoded);
										if($index !== FALSE)
											$GPSchecked="checked";
										else
											$GPSchecked="";
									}
									?>
									<div class="form-group">
										<div class="md-checkbox">
											<input id="tagGPS" type="checkbox" name="tag[]" value="GPS" <?= isset($GPSchecked)?$GPSchecked:'' ?>>
											<label for="tagGPS" class="">GPS</label>
										</div>
									</div>
								</div>

							</div>

							<div class="row">
								<div class="col-sm-6 col-md-3">
									<?php
									if(isset($tagsDecoded))
									{
										$index = array_search("Massaging Seats",$tagsDecoded);
										if($index !== FALSE)
											$MSchecked="checked";
										else
											$MSchecked="";
									}
									?>
									<div class="form-group">
										<div class="md-checkbox">
											<input id="tagMS" type="checkbox" name="tag[]" value="Massaging Seats" <?= isset($MSchecked)?$MSchecked:'' ?>>
											<label for="tagMS" class="">Massaging Seats</label>
										</div>
									</div>
								</div>
								<div class="col-sm-6 col-md-3">
									<?php
									if(isset($tagsDecoded))
									{
										$index = array_search("Built-in Refrigerator",$tagsDecoded);
										if($index !== FALSE)
											$BIRchecked="checked";
										else
											$BIRchecked="";
									}
									?>
									<div class="form-group">
										<div class="md-checkbox">
											<input id="tagBIR" type="checkbox" name="tag[]" value="Built-in Refrigerator" <?= isset($BIRchecked)?$BIRchecked:'' ?>>
											<label for="tagBIR" class="">Built-in Refrigerator</label>
										</div>
									</div>
								</div>
							</div>
						</div>
						<button type="submit" class="btn btn-primary">Save</button>
						<a href="<?= base_url('content/listing'); ?>"><button type="button" class="btn btn-primary">Cancel</button></a>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
</body>
<?php $this->load->view('common/footer'); ?>

<script type="text/javascript">
	$(document).ready(function() {
		updateConfig();
		function updateConfig() {
			var options = {};

			$('#config-demo').daterangepicker(options, function(start, end, label) { console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')'); });
		}

	});
</script>


<script type="text/javascript">


	$(function() {
		$("#customFile").on("change", function(){
			var files = !!this.files ? this.files : [];
           if (!files.length || !window.FileReader) return; // Check if File is selected, or no FileReader support
           if (/^image/.test( files[0].type)){ //  Allow only image upload
            var ReaderObj = new FileReader(); // Create instance of the FileReader
            ReaderObj.readAsDataURL(files[0]); // read the file uploaded
            ReaderObj.onloadend = function(){ // set uploaded image data as background of div
            	$("#display-profile-image").css("background-image", "url("+this.result+")");
            }
        }
    });

	});



	$(function() {
		$("#customFile_1").on("change", function(){
			var files = !!this.files ? this.files : [];
           if (!files.length || !window.FileReader) return; // Check if File is selected, or no FileReader support
           if (/^image/.test( files[0].type)){ //  Allow only image upload
            var ReaderObj = new FileReader(); // Create instance of the FileReader
            ReaderObj.readAsDataURL(files[0]); // read the file uploaded
            ReaderObj.onloadend = function(){ // set uploaded image data as background of div
            	$("#display-profile-image_1").css("background-image", "url("+this.result+")");
            }
        }
    });

	});


	$(function() {
		$("#customFile_2").on("change", function(){
			var files = !!this.files ? this.files : [];
           if (!files.length || !window.FileReader) return; // Check if File is selected, or no FileReader support
           if (/^image/.test( files[0].type)){ //  Allow only image upload
            var ReaderObj = new FileReader(); // Create instance of the FileReader
            ReaderObj.readAsDataURL(files[0]); // read the file uploaded
            ReaderObj.onloadend = function(){ // set uploaded image data as background of div
            	$("#display-profile-image_2").css("background-image", "url("+this.result+")");
            }
        }
    });

	});


	$(function() {
		$("#customFile_3").on("change", function(){
			var files = !!this.files ? this.files : [];
           if (!files.length || !window.FileReader) return; // Check if File is selected, or no FileReader support
           if (/^image/.test( files[0].type)){ //  Allow only image upload
            var ReaderObj = new FileReader(); // Create instance of the FileReader
            ReaderObj.readAsDataURL(files[0]); // read the file uploaded
            ReaderObj.onloadend = function(){ // set uploaded image data as background of div
            	$("#display-profile-image_3").css("background-image", "url("+this.result+")");
            }
        }
    });

	});


	$(function() {
		$("#customFile_4").on("change", function(){
			var files = !!this.files ? this.files : [];
           if (!files.length || !window.FileReader) return; // Check if File is selected, or no FileReader support
           if (/^image/.test( files[0].type)){ //  Allow only image upload
            var ReaderObj = new FileReader(); // Create instance of the FileReader
            ReaderObj.readAsDataURL(files[0]); // read the file uploaded
            ReaderObj.onloadend = function(){ // set uploaded image data as background of div
            	$("#display-profile-image_4").css("background-image", "url("+this.result+")");
            }
        }
    });

	});



</script>

<script type="text/javascript">


	$(function(){
		$('#job_location__custom_coords').change(function() {
			$(".map-box").toggleClass("show");
		});
	});

</script>

<script type="text/javascript">
	$(document).ready(function() {
		$('.js-example-basic-single').select2();
		$('.js-example-basic-multiple').select2();
	});

	function formatState (state) {
		if (!state.id) {
			return state.text;
		}
		var baseUrl = "/user/pages/images/flags";
		var $state = $(
			'<span><img src="' + baseUrl + '/' + state.element.value.toLowerCase() + '.png" class="img-flag" /> ' + state.text + '</span>'
			);
		return $state;
	};

	//preview Images
	$(document).ready(function() {
		// logo image
		var inputLocalFont = document.getElementById("carLogo");
    	inputLocalFont.addEventListener("change",previewLogoImage,false); //bind the function to the input

    	// gallery image
    	var inputLocalFont = document.getElementById("carGallery");
    	inputLocalFont.addEventListener("change",previewGalleryImage,false); //bind the function to the input
    });

	function previewLogoImage(){
		var file_data = $('#carLogo').prop('files')[0];   
		var form_data = new FormData();                  
		form_data.append('carL', file_data);
		$.ajax({
			url: BASE_URL + "content/addTempLogo", 
			type: "POST",             
			data: form_data, 
			contentType: false,       
			cache: false,             
			processData:false,        
			success: function(data)   
			{
				console.log("");
			}
		});
		var fileList = this.files;
		var anyWindow = window.URL || window.webkitURL;
		var objectUrl = anyWindow.createObjectURL(fileList[0]);
		$('#logoDiv').html('<div class="img-box logoCl img-box-big"  style="width:120px;height:120px;background-image: url(' + objectUrl + ')"> </div>');
		window.URL.revokeObjectURL(fileList[0]);
	}

	function previewGalleryImage()
	{
		var form_data = new FormData();
		var ins = document.getElementById('carGallery').files.length;
		for (var x = 0; x < ins; x++) {
			form_data.append("files[]", document.getElementById('carGallery').files[x]);
		}
		$.ajax({
			url: BASE_URL + "content/addTempGallery", 
			type: "POST",             
			data: form_data, 
			contentType: false,       
			cache: false,             
			processData:false,        
			success: function(data)   
			{
				// location.reload();
				$("#galleryDiv").load(location.href + " #galleryDiv");
			}
		});
	}

	function deleteCarGallery($id)
	{
		$divId="GI"+$id;
		$("#"+$divId).remove();
	}
</script>

<script type="text/javascript">
	
	function deleteGalleryImages($name)
	{
		$.ajax({
			type: 'POST',
			url: BASE_URL + "content/removeImageFromSession",
			data: {'imgName':$name},
			success: function (data) {
				data = JSON.parse(data);
				if(data.msg == "success")
				{
					$("#galleryDiv").load(location.href + " #galleryDiv");
				}
			}
		});
	}

	function updateImage($id,$n)
	{
		$.ajax({
			type: 'POST',
			url: BASE_URL + "content/deleteImageOfCar",
			data: {'carId':$id},
			success: function (data) {
				data = JSON.parse(data);
				if(data.response == "deleted")
				{
					$tag="GI"+$n;
					$("#"+$tag).remove();
					$("#galleryDiv").load(location.href + " #galleryDiv");
				}
			}
		});
	}

	$( "#vinSearch" ).click(function() {
		var VinNumber = $("#carVinNUmber").val();

		if(VinNumber.length == 17)
		{
			$.ajax({
				type: 'POST',
				url: BASE_URL + "content/getVINDetails",
				data: {'vinNumber':VinNumber},
				success: function (data) {
					data = JSON.parse(data);
				// console.log(data);
				if(data.error)
				{
					alert("The provided VIN is incorrect.");
				}
				else
				{
					for(var i =0;i<data.decode.length;i++)
					{
						if(data.decode[i].label== "Make")
						{
							var appendingHtml= '<option value="'+ data.decode[i].value +'">'+ data.decode[i].value +'</option>';
							$("#carBrand").html('');
							$("#carBrand").html(appendingHtml);

						}
						else if(data.decode[i].label=="Body")
						{
							var appendingHtml= '<option value="'+ data.decode[i].value +'">'+ data.decode[i].value +'</option>';
							$("#carCategory").html('');
							$("#carCategory").html(appendingHtml);
						}
						else if(data.decode[i].label=="Engine Displacement (ccm)")
						{
							$("#carDisplacement").val(data.decode[i].value);
						}
						else if(data.decode[i].label=="Fuel Type - Primary")
						{
							var appendingHtml= '<option value="'+ data.decode[i].value +'">'+ data.decode[i].value +'</option>';
							$("#carFuel").html('');
							$("#carFuel").html(appendingHtml);
						}
						else if(data.decode[i].label=="Number of Doors")
						{
							$("#carNoOfDoors").val(data.decode[i].value);
						}
						else if(data.decode[i].label=="Number of Seats")
						{
							$("#carMaxPassengers").val(data.decode[i].value);
						}
						else if(data.decode[i].label=="Color")
						{
							var appendingHtml= '<option value="'+ data.decode[i].value +'">'+ data.decode[i].value +'</option>';
							$("#carColor").html('');
							$("#carColor").html(appendingHtml);
						}
						else if(data.decode[i].label=="Series")
						{
							$("#carSeries").val(data.decode[i].value);
						}
						else if(data.decode[i].label=="Drive")
						{
							var appendingHtml= '<option value="'+ data.decode[i].value +'">'+ data.decode[i].value +'</option>';
							$("#carDrivetrains").html('');
							$("#carDrivetrains").html(appendingHtml);
						}
						
					}
				}

			}
		});
		}
		else
		{
			alert("The provided VIN is incorrect.");
		}
		
	});


</script>

<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAMvnpOnA64MXKpF2D8-Fkq2U8mt8rdiXc&amp;libraries=places"></script>
<script src="<?= base_url('assets/js/') ?>jquery.geocomplete.js"></script>
<script>
	// $( "#latitudeInput" ).change(function() {
 //  		 codeLatLng(lat, lng);
	// });
	$(function(){
		$("#geocomplete").geocomplete({
			map: ".map_canvas",
			details: "form ",
			markerOptions: {
				draggable: true
			}
		});

		$("#geocomplete").bind("geocode:dragged", function(event, latLng){
			$("input[name=lat]").val(latLng.lat());
			$("input[name=lng]").val(latLng.lng());
			$("#reset").show();
		});


		$("#reset").click(function(){
			$("#geocomplete").geocomplete("resetMarker");
			$("#reset").hide();
			return false;
		});

		$("#find").click(function(){
			$("#geocomplete").trigger("geocode");
		}).click();
	});

</script>
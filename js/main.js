/*********************
//JQuery du projet //
********************/
$(document).ready(function() {
    /****************************************
    //Bouton retour haut de page du projet //
    ****************************************/
    $('footer > div').hover(
    	function() {
    		$('footer div>span').fadeIn("slow");
    	},
    	function() {
    		$('footer div>span').fadeOut("slow");
    	}
        );
    /********************************************************
    //  Gestion du bouton annuler sur tous les formulaires //
    ********************************************************/
    $('.reset').on('click', function() {
    	window.location.href = '/index.php';
    });
    /***************************************************************
    // On efface le formulaire de log lorsqu'on focus sur newuser //
    ***************************************************************/
    $('#firstName').on('focus', function() {
    	$('#login')[0].reset();
    });
    /************************************************
    // AJAX sur le submit du formulaire de contact //
    ************************************************/
    $('#submitContact').click(function(e) {
    	e.preventDefault();
    	$('#form .error').empty();
    	$('#contactSubmit p').remove();
    	var postdata = $('#form').serialize();
    	$.ajax({
    		method: 'POST',
    		url: '../controller/contact.php',
    		data: postdata,
    		dataType: 'json',
    		success: function(result) {
    			if (result.isSuccess) {
    				$('#contactSubmit').append('<p style="display:block;"> -- Votre message a bien été envoyé.<br> Merci de m\'avoir contacté  <i class="far fa-thumbs-up"></i></p>');
    				$('#form')[0].reset();
    			} else {
    				$('#firstName + .error').html(result.firstNameError);
    				$('#lastName + .error').html(result.lastNameError);
    				$('#email + .error').html(result.emailError);
    				$('#phone + .error').html(result.phoneError);
    				$('#message + .error').html(result.messageError);
    			}
    		},
    		error: function(result) {
    			console.log('Pas de valeur', result);
    		}
    	});
    });
    /**********************************************************
    // AJAX sur le submit du formulaire de connexion newuser //
    **********************************************************/
    $('#newUserLog').click(function(e) {
    	$('#newUserName .error').empty();
    	$("#newUserName > fieldset > p").empty();
    	$('#newUserName .loader').css('display', 'inline-block').fadeIn('slow');
    	$('#newUserLog').prop("disabled", "disabled");
    	var fd = new FormData($('#newUserName')[0]);
    	e.preventDefault();
    	$.ajax({
    		url: '../controller/connexion.php',
    		method: 'POST',
    		data: fd,
    		dataType: 'json',
    		processData: false,
    		contentType: false,
    		success: function(data) {
    			if (data.isSuccess == true) {
    				$.ajax({
    					method: 'POST',
    					url: '../model/connectData.php',
    					data: fd,
    					dataType: 'json',
    					processData: false,
    					contentType: false,
    					success: function(datafirst) {
    						if (datafirst.result == true) {
                                // Si tous les champs sont correctement remplis on récupère le mail
                                var monmail = $('#email').val();
                                $('#newUserName')[0].reset();
                                $('#newUserName > fieldset:nth-child(2)').remove();
                                $('#newUserName > fieldset:nth-child(1) > p').remove();
                                $('#newUserName > fieldset:nth-child(1)').html('<p><i class="far fa-check-circle" style="color:blue"></i> -- Votre inscription a bien été effectuée !!! <br> Vous pouvez vous connecter dès à présent </p>');
                                $('#newUser').css({
                                	'vertical-align': 'bottom',
                                	'text-align': 'center'
                                });
                                $('#logmail').val(monmail);
                                $('#logpass').focus();
                                $('#newUserLog').prop("disabled", false);
                                $('.loader').fadeOut("300");
                            } else {
                            	var monmail = $('#email').val();
                            	$('#newUserName')[0].reset();
                            	$('#newUserName > fieldset:nth-child(2)').remove();
                            	$('#newUserName > fieldset:nth-child(1) > p').remove();
                            	$('#newUserName > fieldset:nth-child(1)').html('<p class="error"><i class="fas fa-exclamation-triangle" style="color:red"></i> -- Il existe déjà un utilisateur inscrit avec cet email </p>');
                            	$('#newUser').css({
                            		'vertical-align': 'bottom',
                            		'text-align': 'center'
                            	});
                            	$('#logmail').val(monmail);
                            	$('#logpass').focus();
                            	$('#newUserLog').prop("disabled", false);
                            	$('.loader').fadeOut("300");
                            }
                        },
                        error: function(datafirst) {
                        	console.log('Error', datafirst);
                        }
                    });
    			} else {
                    // On affiche les messages d'erreurs si les champs sont mal remplis
                    $('#firstName + .error').html(data.firstnameError);
                    $('#lastName + .error').html(data.lastnameError);
                    $('#email + .error').html(data.emailError);
                    $('#pass + .error').html(data.passwordError);
                    $('#newUserLog').prop("disabled", false);
                    $('.loader').fadeOut("300");
                }
            },
            error: function(data) {
            	$('#newUserLog').prop("disabled", false);
            	$('.loader').fadeOut("300");
            	console.log('Erreur !!!', data);
            }
        });
    });
    /***********************************************
    //  Ajax sur le submit du formulaire de login //
    **********************************************/
    $('#logUser').click(function(e) {
    	$('#logUser').prop("disabled", "disabled");
    	$('#login .error').empty();
    	$('#login > fieldset > p').empty();
    	e.preventDefault();
    	$.ajax({
    		method: 'POST',
    		url: '../controller/connexion.php',
    		data: {
    			pass: $("#logpass").val(),
    			email: $("#logmail").val(),
    			log: $("#login input[type=hidden]").val()
    		},
    		dataType: 'json',
    		success: function(datasecond) {
    			if (datasecond.isSuccess == true) {
    				$('#login .loader').css('display', 'inline-block').fadeIn('slow');
    				$.ajax({
    					method: 'POST',
    					url: '../model/loginData.php',
    					data: {
    						email: $("#logmail").val(),
    						pass: $("#logpass").val()
    					},
    					dataType: 'json',
    					success: function(resultfirst) {
    						if (resultfirst.result == true) {
    							$('#login fieldset:nth-child(1) > strong ~ p').remove();
    							$('<p style="color:blue"><i class="far fa-check-circle"></i> -- Connexion réussie -- </p>').insertAfter('#login > fieldset:nth-child(1) > strong');
    							$('#logUser').prop("disabled", false);
                                // Si tout est ok on post les deux paramètres
                                $('#login').submit();
                                $('.loader').fadeOut("300");
                                // Sinon
                            } else if (resultfirst.result == false) {
                            	$('#login fieldset:nth-child(1) > strong ~ p').remove();
                            	$('.loader').fadeOut("300");
                            	$('<p class="error"><i class="fas fa-exclamation-triangle"></i> -- Le mail ou le mot de passe est incorrect : ( <br> Accès refusé -- </p>').insertAfter('#login > fieldset:nth-child(1) > strong');
                            	$('#logUser').prop("disabled", false);
                            } else {
                            	$('#login fieldset:nth-child(1) > strong ~ p').remove();
                            	$('.loader').fadeOut("300");
                            	$('<p class="error"><i class="fas fa-exclamation-triangle" style="color:red"></i> -- ' + resultfirst.result + ' -- </p>').insertAfter('#login > fieldset:nth-child(1) > strong');
                            	$('#logUser').prop("disabled", false);
                            }
                        },
                        error: function(resultfirst) {
                        	$('.loader').fadeOut("300");
                        	$('#login fieldset:nth-child(1) > strong ~ p').remove();
                        	$('<p class="error"><i class="fas fa-exclamation-triangle" style="color:red"></i> -- Accès à la base de données impossible -- </p>').insertAfter('#login > fieldset:nth-child(1) > strong');
                        	$('#logUser').prop("disabled", false);
                        }
                    });
    			} else {
    				$('#logUser').prop("disabled", false);
    				$('#logmail + .error').html(datasecond.emailError);
    				$('#logpass + .error').html(datasecond.passwordError);
    			}
    		},
    		error: function(datasecond) {
    			$('#logUser').prop("disabled", false);
    			console.log('Error', datasecond);
    		}
    	});
    });
    /********************************************
    // Ajax sur Formulaire Ajout transporteur  //
    *******************************************/
    $('#carrierSubmit').on('click', function(e) {
    	e.preventDefault();
    	$('#carrierSubmit').prop("disabled", "disabled");
    	$("#carrier-add fieldset:nth-child(1) .error").empty();
    	$(".input-file").change(function() {
    		$("#carrier-add fieldset:nth-child(1) .error").empty();
    		$('#carrierDataFile').removeClass('pulse');
    	});
    	try {
    		$.ajax({
    			url: '../controller/newCarrier.php',
    			method: 'POST',
    			dataType: 'json',
    			data: {
    				file: $('#carrierDataFile').val(),
    				size: $('#carrierDataFile')[0].files[0].size,
    				type: $('#carrierType').val(),
    				nom: $('#carrierName').val(),
    				newCarrier: "first"
    			},
    			success: function(datathird) {
    				if (datathird.result == true) {
    					newCarrier: $('#carrier-add input[type="hidden"]').attr('name', 'continue');
    					nom: $('#carrierName').attr('name', datathird.carrierName);
    					var fdata = new FormData($('#carrier-add')[0]);
    					$("#carrier-add fieldset:nth-child(1) .error").html("<p class='comment' style='color:blue'><i class='far fa-check-circle'></i> -- Fichier ' " + datathird.pathInfo.basename + " '...upload du fichier en cours...</p>");
    					$('#carrier-add').attr('action', '../controller/uploadFile.php');
    					$('#carrierSubmit').click();
    					$.ajax({
    						url: '../controller/uploadFile.php',
    						method: 'POST',
    						xhr: function() {
                                myXhr = $.ajaxSettings.xhr(); // xhr qui traite la barre de progression
                                if (myXhr.upload) { // vérifie si l'upload existe
                                	myXhr.upload.addEventListener('progress', afficherAvancement, false);
                            }
                            return myXhr;
                        },
                        //Données du formulaire envoyé
                        data: fdata,
                        //Options signifiant à jQuery de ne pas s'occuper du type de données
                        dataType: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(data) {
                         if (data.result == true) {
                          $("#carrier-add fieldset:nth-child(1) .error").html("<p class='comment' style='color:blue'><i class='far fa-check-circle'></i> -- Fichier ' " + datathird.pathInfo.basename + " '...uploadé avec succès !!!</p>");
                          $('#uploadbar').fadeIn();
                          $('#carrierSubmit').prop("disabled", 'disabled');
                          $('<button type="button" id="carrierReset" onclick="location.reload(true);">Ajouter</button>').insertAfter("#carrier-add button[type='reset']");
                          $('#carrier-add button[type="reset"').hide(300);
                      } else {
                          console.log('Réponse du deuxieme passage', data.fileError);
                          $("#carrier-add fieldset:nth-child(1) .error").html("<p class='error'><i class='fas fa-exclamation-triangle' style='color:red'></i> --" + data.fileError + "</p>");
                          $('#carrierSubmit').prop("disabled", false);
                      }
                  },
                  error: function(data) {
                     console.log('Erreur', data);
                     $('#carrierSubmit').prop("disabled", false);
                 }
             });

    					function afficherAvancement(e) {
    						if (e.lengthComputable) {
    							$('progress').attr({
    								value: e.loaded,
    								max: e.total
    							});
    						}
    					}
    				}
    				else {
    					$("#carrier-add fieldset:nth-child(1) .error").html("<p class='error'><i class='fas fa-exclamation-triangle' style='color:red'></i> --" + datathird.fileError + "</p>");
    					$('#carrierSubmit').prop("disabled", false);
    				}
    			},
    			error: function(xhr, ajaxOptions, thrownError, datathird) {
    				$("#carrier-add fieldset:nth-child(1) .error").html("<p class='error'><i class='fas fa-exclamation-triangle' style='color:red'></i> --" + datathird.fileError + "</p>");
    				$('#carrierSubmit').prop("disabled", false);
    			}
    		});
        } catch (err) {
         error: $("#carrier-add fieldset:nth-child(1) .error").html("<p class='error'><i class='fas fa-exclamation-triangle' style='color:red'></i> --Vous devez imperativement sélectionner un fichier '.csv' pour pouvoir poursuivre</p>");
         e.preventDefault();
         $('#carrierDataFile').addClass('pulse');
         $('#carrierSubmit').prop("disabled", false);
     }
 });
    /*************************************************
    // Animation du bouton sandwich application.php //
    *************************************************/
    $('.sandwichmenu').on('click', function() {
    	this.classList.toggle("change");
    	if ($(this).attr('class') == 'sandwichmenu') {
    		$('.sandwichmenu + ul').hide('300');
    	} else {
    		$('.sandwichmenu + ul').show('300');
    	}
    });
    /*******************************
    // Clic sur le lien du footer //
    *******************************/
    $('#license > a').click(function(e) {
    	e.preventDefault();
    	$('html').css('background-color', 'hsla(0, 0%, 0%, 0.46)');
    	$('<div class="message">Elève de la 3W Academy de Villeurbanne session 2018<br>Je fais des sites Web de qualité<br>Pour tous renseignemants complémentaires<br>rendez-vous sur ma page <a href="../controller/contact.php#form"><b>contact.</b></a><br>A très vite ; )<div id="close" title="Fermer la fenêtre">X</div></div>').insertAfter('main');
    	$('#close').on('click', function() {
    		$('.message').remove();
    		$('html').css('background-color', 'transparent');
    	});
    });
    /***************************************
    // Séléction suppression transporteur //
    **************************************/
    $('li[data-checkbox] i').click(function(e) {
    	e.preventDefault();
    	var index = $('.fa-trash').index(this);
    	var checkbox = $("li[data-checkbox]>input[type=checkbox]");
    	if ($(checkbox[index]).prop("checked")) {
    		$(checkbox[index]).prop("checked", false);
    		$(this).css('color', '#45647e');
    	} else {
    		$(checkbox[index]).prop("checked", true);
    		$(this).css('color', 'red');
    		$("#carrierDel-sub").fadeIn(300);
    		$("#userDel-sub").fadeIn(300);
    	}
    });
    $("li[data-checkbox]>input[type=checkbox]").change(function(e) {
    	var index = $("li[data-checkbox]>input[type=checkbox]").index(this);
    	var trash = $('.fa-trash');
    	if ($(this).prop("checked") == true) {
    		$(trash[index]).css("color", "red");
    	} else if ($(this).prop("checked") == false) {
    		$(trash[index]).css("color", "#45647e");
    	}
    });
    /***************************************************
    // Bouttton annuler dans suppression transporteur //
    **************************************************/
    $('#carrier-del button[type=reset]').click(function(event) {
    	$('.fa-trash').css('color', '#45647e');
    });
    /*****************************
    // Suppression transporteur //
    ****************************/
    $('#del-carrierSubmit').on('click', function(event) {
    	event.preventDefault();
    	var key = [];
    	var value = [];
    	var sThisVal = [];
    	$('input:checkbox').each(function() {
    		if ((this).checked) {
    			key.push($(this).val());
    			key.push($(this).data('checkbox'));
    		}
    		value.push(key);
    		key = [];
    	});
    	sThisVal.push(value);
    	var result = false;
    	$("li[data-checkbox]>input[type=checkbox]").each(function(e) {
    		if ($(this).prop("checked") == true) {
    			result = true;
    		}
    	});
    	if (result == true) {
    		if (confirm('Vous êtes sur le point d\'effectuer une suppression\nVoulez-vous vraiment poursuivre?')) {
    			console
    			$.ajax({
    				url: '../controller/delCarrier.php',
    				type: 'POST',
    				dataType: 'json',
    				data: {
    					val: sThisVal
    				},
    				success: function(datafour) {
    					if (datafour.result == true) {
    						$('#del-carrierSubmit').hide()
    						$("#carrierDel-sub > button[type=reset]").hide();
    						$("<p class='comment' style='color:blue;display:inline-block;'><i class='far fa-check-circle'></i>" + datafour.message + "</p>").insertAfter('#carrierDel-sub button[type = reset]');
    						$('<button type=button  id="actualiser">Recharger cette page</button>').insertAfter('.comment');
    						$('#actualiser').on('click', function() {
    							$('#carrierDel-sub .loader').css('display', 'inline-block').fadeIn('slow');
    							location.reload();
    						});
    					} else {
    						$('#del-carrierSubmit').hide();
    						$("#carrierDel-sub > button[type=reset]").hide();
    						$("<p class='error' style='display:inline-block'><i class='fas fa-exclamation-triangle' style='color:red;'></i>" + datafour.message + "</p>").insertAfter('#carrierDel-sub button[type = reset]');
    					}
    				},
    				error: function(datafour) {
    					console.log('Error', datafour);
    				}
    			});
    		}
    	}
    });
    /****************************************************
    //  Sauvegarde des données dans l'application      //
    ***************************************************/
    $('.butUsaveApp').on('click', function(e) {
    	e.preventDefault();
    	var gazole = [];
    	$('.gazoleInp').each(function() {
    		if (!$.isNumeric($(this).val())) {
    			$(this).css('color', 'red');
    			alert('Valeur non numeric');
    			return false;
    		}
    		var value = $(this).val();
    		$(this).css('color', '#000000');
    		gazole.push(value);
    	});
    	$.ajax({
    		url: '../controller/appDataSave.php',
    		type: 'POST',
    		dataType: 'json',
    		data: {
    			val: gazole
    		},
    		success: function(datafive) {
    			console.log('Success!!!', datafive);
    			if (datafive.result == true) {
    				$('<p class="comment" style="display:block;text-align:center"><i class="far fa-check-circle" style="color:blue"></i>--' + datafive.message + '</p>').insertAfter('fieldset:nth-child(3)');
    				$('.container > p:nth-child(4)').delay(5000).queue(function() {
    					$(this).remove();
    				});
    			} else {
    				$('<p class="error" style="display:block;text-align:center"><i class="fas fa-exclamation-triangle" style="color:red"></i>--' + datafive.message + '</p>').insertAfter('fieldset:nth-child(3)');
    				$('.container > p:nth-child(4)').delay(9000).queue(function() {
    					$(this).remove();
    				});
    			}
    		},
    		error: function(datafive) {
    			console.log('Pas bon!!:', datafive);
    			$('<p class="error" style="display:block;text-align:center"><i class="fas fa-exclamation-triangle" style="color:red"></i>--Echec de l\'enregistrement...Vérifiez votre saise avant de réessayer...</p>').insertAfter('fieldset:nth-child(3)');
    			$('.container > p:nth-child(4)').delay(9000).queue(function() {
    				$(this).remove();
    			});
    		}
    	});
    });
    /***************************************************
    // Bouttton annuler dans suppression compte user  //
    **************************************************/
    $('#userSuppress-del button[type=reset]').click(function(event) {
    	$('.fa-trash').css('color', '#45647e');
    	$('.error').remove();
    });
    /*****************************
    // Suppression compte user  //
    ****************************/
    $('#user-suppressSubmit').on('click', function(event) {
    	event.preventDefault();
    	var key = [];
    	var value = [];
    	var sThisVal = [];
    	$('input:checkbox').each(function() {
    		if ((this).checked) {
    			key.push($(this).val());
    		}
    		value.push(key);
    		key = [];
    	});
    	sThisVal.push(value);
    	console.log(sThisVal);
    	var result = false;
    	$("li[data-checkbox]>input[type=checkbox]").each(function(e) {
    		if ($(this).prop("checked") == true) {
    			result = true;
    		}
    	});
    	if (result == true) {
    		if (confirm('Vous êtes sur le point d\'effectuer une suppression\nVoulez-vous vraiment poursuivre?')) {
    			$.ajax({
    				url: '../controller/userSuppress.php',
    				type: 'POST',
    				dataType: 'json',
    				data: {
    					val: sThisVal[0],
    				},
    				success: function(datasixt) {
    					if (datasixt.result == "reload") {
    						setTimeout(function() {
    							window.location.href = "../index.php";
    						}, 5000);
    					}
    					if (datasixt.result == true ) {
    						$('#user-suppressSubmit').hide()
    						$("#userDel-sub > button[type=reset]").hide();
    						$("<p class='comment' style='color:blue;display:inline-block;'><i class='far fa-check-circle'></i>" + datasixt.message + "</p>").insertAfter('#userSuppress-del button[type = reset]');
    						$('<button type=button  id="actualiser">Recharger cette page</button>').insertAfter('.comment');
    						$('#actualiser').on('click', function() {
    							$('#userSuppress-del .loader').css('display', 'inline-block').fadeIn('slow');
    							location.reload();
    						});
    					} else {
    						$('#user-suppressSubmit').hide();
    						$("#userSuppress-del > button[type=reset]").hide();
    						$("<p class='error' style='display:inline-block'><i class='fas fa-exclamation-triangle' style='color:red;'></i>" + datasixt.message + "</p>").insertAfter('#userSuppress-del button[type = reset]');
    					}
    				},
    				error: function(datasixt) {
    					console.log('Error', datasixt);
    					$('.error').remove();
    					$("<p class='error' style='display:inline-block'><i class='fas fa-exclamation-triangle' style='color:red;'></i>--Une erreur est survenue...Echec de la suppression... </p>").insertAfter('#userSuppress-del button[type = reset]');
    				}
    			});
    		}
    	}
    });
});
<?php
/**
 * Astra Child Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Astra Child
 * @since 1.0.0
 */

/**
 * Define Constants
 */
define( 'CHILD_THEME_ASTRA_CHILD_VERSION', '1.0.0' );

/**
 * Enqueue styles
 */
function child_enqueue_styles() {

	wp_enqueue_style( 'astra-child-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), CHILD_THEME_ASTRA_CHILD_VERSION, 'all' );

}

add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );

/**
 * Construtor de Perfil de Usuário para WordPress
 * https://www.ramirolobo.com/construtor-de-perfil-de-usuario-para-wordpress/
 */
 
 class miro_upb_class {
	public function __construct() {
		
		// Grava os dados dos campos extras no banco de dados (WordPress).
		add_action( "user_register", array($this, "user_register_custom_fields") );
		add_action( "profile_update", array($this, "user_register_custom_fields") );
		
		// Dispara no final do novo formulário do usuário.
		add_action( "user_new_form", array($this,"admin_registration_form") );
		
		// Dispara após a tabela de configurações "Sobre você" na tela de edição de "Perfil".
		add_action( "show_user_profile", array($this,"show_extra_profile_fields") );
		
		// Dispara após a tabela de configurações "Sobre o usuário" na tela "Editar usuário".
		add_action( "edit_user_profile", array($this,"show_extra_profile_fields") );

		// Esta ação dispara após o campo "E-mail" no formulário de registro do usuário do WordPress.
		add_action( "register_form", array($this, "add_fields_register_form") );

	}
 

	function add_fields_register_form() {
		$miro_upb_aceite_email = !empty( $_POST["miro_upb_aceite_email"] ) ? $_POST["miro_upb_aceite_email"] : "";
?>

<p>
<label for="miro_upb_aceite_email">Aceite e-mail<br/>
<input type="checkbox" 
	name="miro_upb_aceite_email"
	id="miro_upb_aceite_email" value="1"> Desejo receber ofertas por e-mail
</label>
</p>

<?php
	}

	function user_register_custom_fields( $user_id ) {
		$miro_upb_aceite_email = isset( $_POST["miro_upb_aceite_email"] ) ? sanitize_text_field($_POST["miro_upb_aceite_email"]) : "0";

		update_user_meta( $user_id, "miro_upb_aceite_email", $miro_upb_aceite_email );

	}

	/**
	* $operation também pode ser "add-existing-user"
	*
	* Você pode desejar executar alguma ação neste ponto,
	* como por exemplo cadastrar o usuário no seu sistema de 
	* automação ou e-mail marketing.
	*/
	function admin_registration_form( $operation ) {
		if ( "add-new-user" !== $operation ) {
			return;
		}
?>
<!-- <h3>Título da seção se desejar</h3> -->
<?
		$miro_upb_aceite_email = !empty( $_POST["miro_upb_aceite_email"] ) ? $_POST["miro_upb_aceite_email"] : "";
?>

<table class="form-table">
<tr>
<th><label for="miro_upb_aceite_email">Aceite e-mail</label> <span class="description">*</span></th>
<td>
<input type="checkbox" 
	name="miro_upb_aceite_email"
	id="miro_upb_aceite_email" value="1"> Desejo receber ofertas por e-mail
</td>
</tr>
</table>

<?php
	}

	function show_extra_profile_fields( $user ) {
?>
<!-- <h3>Título da seção se desejar</h3> -->

<table class="form-table">
	<tr>
		<th><label for="miro_upb_aceite_email">Aceite e-mail</label></th>
		<td>
<input type="checkbox" 
	name="miro_upb_aceite_email"
	id="miro_upb_aceite_email"<?php checked( get_the_author_meta( "miro_upb_aceite_email", $user->ID ), "1" ); ?>
	value="1"> Desejo receber ofertas por e-mail
		</td>
	</tr>
</table>

<?php
	}

}
$miro_upb_obj = new miro_upb_class();

/* Dados para importação - Copie sem as tags de comentários
{"upb":"1","type_of_output":"function","projectprefix":"miro_upb","plugin_wordpress":"1","field-type":["checkbox"],"field-name":["aceite_email"],"field-label":["Aceite e-mail"],"field-descriptions":["Desejo receber ofertas por e-mail"],"field-options":[""],"LjBExJNMKTRPhtXm":"mSW8bT0yu","uhcyJoaSwXWHnv":"xDeq0V","dALQ-Sg":"CUgHXGW52QPz4b","kVUxciEwyRFQS":"P6*qdtU"}
*/

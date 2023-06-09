<?php
/**
 * Biblioteca de fun��es
 * by Odi - Sisnema - 12/06/2007
 */

/**
 * fun��o que retorna a data de hoje no formato portugu�s brasileiro
 */
function data() {
	return date("d/m/Y");
}

/**
 * fun��o que retorna a hora : minuto : segundo de agora
 */
function hora() {
	return date("H:i:s");
}

/**
 * recebe um n�mero e retorna no formato moeda BR 
 */
function moeda($val) {
	$val = number_format($val, 2, '.', ',');
	return $val;
}

/**
 * recebe um valor no formato moeda BR e retorna um n�mero
 */
function numero($val) {
	$val = str_replace('.', '', $val); //primeiro tira o ponto 1.519,80
	$val = str_replace(',', '.', $val); //troca a v�rgula por ponto 1519.8
	return $val * 1; //gambeta pra tirar o zero do final se existir
}

function telefone($val) {
	$val = str_replace('(', '', $val); //primeiro tira o ponto 1.519,80
	$val = str_replace(')', '', $val); //primeiro tira o ponto 1.519,80
	$val = str_replace(' ', '', $val); //troca a v�rgula por ponto 1519.8
	$val = str_replace('-', '', $val); //primeiro tira o ponto 1.519,80
	return $val ; //gambeta pra tirar o zero do final se existir
}
/**
 * recebe uma data no formato aaaa-mm-dd e retorna no formato BR
 */
function dataBR($val, $sep = '-') {
	$vet = explode($sep, $val);
	return $vet[2].'/'.$vet[1].'/'.$vet[0];
}

/**
 * recebe uma data no formata dd/mm/aaaa e retorna no formato mysql
 */
function dataMY($val, $sep = '/') {
	$vet = explode($sep, $val);
	return $vet[2].'-'.$vet[1].'-'.$vet[0];
}

?>
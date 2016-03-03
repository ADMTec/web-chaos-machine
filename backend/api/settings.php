<?php
/**
 * Classe de configuração da Chaos Machine
 * Altere aqui os dados essenciais para que o sistema funcione corretamente.
 * 
 * By Leandro Daldegam
 */
class Settings {

	/**
	 * Configurações da conexão com Microsoft SQL Server
	 */
	public $database = array(
		// Driver de conexão: mssql, sqlsrv
		"driver" => "mssql",

		// Endereço do host
		"address" => "127.0.0.1",

		// Usuário no banco de dados
		"username" => "sa",

		// Senha do usuário
		"password" => "123456",

		// Nome dos bancos de dados
		"database" => array(
			// Banco de dados onde estão as contas do servidor
			"account" => "Me_MuOnline",

			// Banco de dados onde estão os personagens do servidor
			"character" => "MuOnline"
		),

		// O Servidor utiliza MD5 nas senhas
		"md5EncriptyPasswords" => false,

		// Versão do banco de dados: 
		// 	1 para 10 bytes [Versões 97d]
		// 	2 para 16 bytes [Versões Season 1/2/3/4/5/6 sem Inventário Extendido]
		// 	3 para 16 bytes [Versões Season 6.2+ com Inventário Extendido]
		"databaseModel" => 2
	);

    /**
     * Template da maquina chaos
     *     Valores possíveis:
     *         v97d
     *         season6
     */
    public $template = "v97d";

	/**
	 * Configurações das combinações da maquina chaos
	 * Veja abaixo o que significa cada umas das opções
	 * 		typeMix: 
	 *			0: Significa uma combinação de upgrade de level (por exemplo: +13 para +14)
	 *			1: Significa uma combinação que irá consumir todos os itens colocados na maquina e irá gerar um novo item
	 *
	 *		excellentsRequirements:
	 *			Quantidade mínima de opções excelentes que o item precisa ter para poder fazer o upgrade de level
	 *			Por exemplo, se voce colocar 0 qualquer item poderá ser upado, se voce colocar 6 o item precisa
	 *			ser ter todas as options excelentes para poder ser combinado. Valores possíveis: 0 à 6
	 *
	 *		amount:
	 *			Quantidade necessária de um determimado item
	 *
	 *		section:
	 *			Número da categoria referente ao item no "item.txt" do servidor
	 *
	 *		index:
	 *			Número do index referente ao item no "item.txt" do servidor
	 *
	 *		level:
	 *			Level do item. 
	 *			Valores possíveis: 0 à 15
	 *
	 *		options:
	 *			Opções adicionais do item. 
	 *			Valores possíveis: 
	 *				0 para +0
	 *				1 para +4
	 *				2 para +8
	 *				3 para +12
	 *				4 para +16
	 *				5 para +20
	 *				6 para +24
	 *				7 para +28
	 *
	 *		luck:
	 *			Luck no item. 
	 *			Valores possíveis:
	 *				false para Não
	 *				true para Sim
	 *
	 *		skill:
	 *			Skill no item. 
	 *			Valores possíveis:
	 *				false para Não
	 *				true para Sim
	 *
	 *		excellents:
	 *			Opções excelentes do item.
	 *			É necessário configurar as 6 opções excelentes do item.
	 *			Por exemplo:
	 *				Item Full: array(true, true, true, true, true, true)
	 *				Item Normal: array(false, false, false, false, false, false)
	 *			Valores possíveis:
	 *				false para Não
	 *				true para Sim
	 */
	public $machineMixes = array(
		// INICIO DE UMA COMBINAÇÃO
        array(
			// Detalhes da combinação
            "name" => "Level +11 para +12", // Nome da combinação
            "details" => array(
            	"typeMix" => 0, // Vide explicação acima
            	"percentage" => 50, // Porcentagem de acerto da combinação 0 a 100
            	"excellentsRequirements" => 1 // Vide explicação acima
            ),
            // Itens necessários para a combinação
            "requirements" => array( 
                array("amount" => 3, "section" => 14, "index" => 13, "level" => 0, "options" => 0, "luck" => false ), //Bless
                array("amount" => 3, "section" => 14, "index" => 14, "level" => 0, "options" => 0, "luck" => false ), //Soul
                array("amount" => 1, "section" => 12, "index" => 15, "level" => 0, "options" => 0, "luck" => false ), //Chaos
            ),
            // Resultado da combinação
            "result" => array(
            	"oldLevel" => 11, // Level que o item precisa estar
            	"newLevel" => 12 //Level do item após a combinação
            )
        ),
        // FIM DE UMA COMBINAÇÃO
        
        // INICIO DE UMA COMBINAÇÃO
        array(
			// Detalhes da combinação
            "name" => "Level +12 para +13", // Nome da combinação
            "details" => array(
            	"typeMix" => 0, // Vide explicação acima
            	"percentage" => 100, // Porcentagem de acerto da combinação 0 a 100
            	"excellentsRequirements" => 1 // Vide explicação acima
            ),
            // Itens necessários para a combinação
            "requirements" => array( 
                array("amount" => 4, "section" => 14, "index" => 13, "level" => 0, "options" => 0, "luck" => false ), //Bless
                array("amount" => 4, "section" => 14, "index" => 14, "level" => 0, "options" => 0, "luck" => false ), //Soul
                array("amount" => 1, "section" => 12, "index" => 15, "level" => 0, "options" => 0, "luck" => false ), //Chaos
            ),
            // Resultado da combinação
            "result" => array(
            	"oldLevel" => 12, // Level que o item precisa estar
            	"newLevel" => 13 //Level do item após a combinação
            )
        ),
        // FIM DE UMA COMBINAÇÃO
        
        // INICIO DE UMA COMBINAÇÃO
        array(
			// Detalhes da combinação
            "name" => "Level +13 para +14", // Nome da combinação
            "details" => array(
            	"typeMix" => 0, // Vide explicação acima
            	"percentage" => 100, // Porcentagem de acerto da combinação 0 a 100
            	"excellentsRequirements" => 1 // Vide explicação acima
            ),
            // Itens necessários para a combinação
            "requirements" => array( 
                array("amount" => 5, "section" => 14, "index" => 13, "level" => 0, "options" => 0, "luck" => false ), //Bless
                array("amount" => 5, "section" => 14, "index" => 14, "level" => 0, "options" => 0, "luck" => false ), //Soul
                array("amount" => 1, "section" => 12, "index" => 15, "level" => 0, "options" => 0, "luck" => false ), //Chaos
            ),
            // Resultado da combinação
            "result" => array(
            	"oldLevel" => 13, // Level que o item precisa estar
            	"newLevel" => 14 //Level do item após a combinação
            )
        ),
        // FIM DE UMA COMBINAÇÃO
        
        // INICIO DE UMA COMBINAÇÃO
        array(
			// Detalhes da combinação
            "name" => "Level +14 para +15", // Nome da combinação
            "details" => array(
            	"typeMix" => 0, // Vide explicação acima
            	"percentage" => 100, // Porcentagem de acerto da combinação 0 a 100
            	"excellentsRequirements" => 1 // Vide explicação acima
            ),
            // Itens necessários para a combinação
            "requirements" => array( 
                array("amount" => 6, "section" => 14, "index" => 13, "level" => 0, "options" => 0, "luck" => false ), //Bless
                array("amount" => 6, "section" => 14, "index" => 14, "level" => 0, "options" => 0, "luck" => false ), //Soul
                array("amount" => 1, "section" => 12, "index" => 15, "level" => 0, "options" => 0, "luck" => false ), //Chaos
            ),
            // Resultado da combinação
            "result" => array(
            	"oldLevel" => 14, // Level que o item precisa estar
            	"newLevel" => 15 //Level do item após a combinação
            )
        ),
        // FIM DE UMA COMBINAÇÃO
        
        // INICIO DE UMA COMBINAÇÃO
		array(
			// Detalhes da combinação
            "name" => "Wings of Dragon  (Level II)", // Nome da combinação
            "details" => array(
            	"typeMix" => 1, // Vide explicação acima
            	"percentage" => 80 // Porcentagem de acerto da combinação 0 a 100
            ),
            // Itens necessários para a combinação
            "requirements" => array( 
                array("amount" => 3, "section" => 14, "index" => 13, "level" => 0, "options" => 0, "luck" => false ), //Bless
                array("amount" => 6, "section" => 14, "index" => 14, "level" => 0, "options" => 0, "luck" => false ), //Soul
                array("amount" => 1, "section" => 12, "index" => 15, "level" => 0, "options" => 0, "luck" => false ), //Chaos                 
                array("amount" => 2, "section" => 0, "index" => 18, "level" => 0, "options" => 0, "luck" => false ), //Thunder Sword
            ),
            // Resultado da combinação
            "result" => array(
            	"section" => 12, 
            	"index" => 5, 
            	"level" => 11, 
            	"options" => 7, 
            	"luck" => true, 
            	"skill" => true, 
            	"excellents" => array(true, true, true, true, true, true)
            )
        ),
        // FIM DE UMA COMBINAÇÃO
	);
}
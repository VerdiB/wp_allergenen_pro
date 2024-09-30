<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class Allergens_Dietary_Ictoria_Activator {
	private static $counter = 0;

	public static function activate() {
		if ( self::$counter === 0 ) {
			self::create_tables();
			self::insert_standard_data();
			// self::add_fk_tables();
			++self::$counter;
		}
		if ( self::$counter > 0 ) {
			return;
		}
	}

	private static function create_tables() {
		global $wpdb;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$sql_attachments = $wpdb->query(
			"CREATE TABLE IF NOT EXISTS {$wpdb->prefix}allergens_dietary_ictoria_attachments(
        attachment_name VARCHAR(255) NOT NULL PRIMARY KEY,
        attachment_path VARCHAR(255))"
		);

		$sql_allergy = $wpdb->query(
			"CREATE TABLE IF NOT EXISTS {$wpdb->prefix}allergens_dietary_ictoria_allergy(
        allergy_name VARCHAR(50) NOT NULL PRIMARY KEY,
        allergy_description VARCHAR(255),
        is_allergy BOOLEAN NOT NULL DEFAULT 1)"
		);

		$sql_allergy_attachment = $wpdb->query(
			"CREATE TABLE IF NOT EXISTS {$wpdb->prefix}allergens_dietary_ictoria_allergy_attachment(
        allergy_name VARCHAR(50) NOT NULL,
        attachment_name VARCHAR(255) NOT NULL,
        PRIMARY KEY (allergy_name, attachment_name),
        CONSTRAINT FK_AllergyAttch_Allergy
        FOREIGN KEY (allergy_name) REFERENCES {$wpdb->prefix}allergens_dietary_ictoria_allergy(allergy_name) ON UPDATE CASCADE ,
        CONSTRAINT FK_AllergyAttch_Attch
        FOREIGN KEY (attachment_name) REFERENCES {$wpdb->prefix}allergens_dietary_ictoria_attachments(attachment_name) ON UPDATE CASCADE) 
        "
		);

		$sql_allergy_product = $wpdb->query(
			"CREATE TABLE IF NOT EXISTS {$wpdb->prefix}allergens_dietary_ictoria_allergy_product(
        product_id BIGINT NOT NULL,
        allergy_name VARCHAR(50) NOT NULL,
        PRIMARY KEY (product_id, allergy_name),
        CONSTRAINT FK_AllergyProduct_WCproduct
        FOREIGN KEY (product_id) REFERENCES {$wpdb->prefix}wc_product_meta_lookup(product_id),
        CONSTRAINT FK_AllergyProduct_Allergy
        FOREIGN KEY (allergy_name) REFERENCES {$wpdb->prefix}allergens_dietary_ictoria_allergy(allergy_name) ON UPDATE CASCADE )"
		);
		dbDelta( $sql_attachments );
		dbDelta( $sql_allergy );
		dbDelta( $sql_allergy_attachment );
		dbDelta( $sql_allergy_product );

	}

	

	private const OPTIONS1 = array(
			'peanuts'     => array(
				'category'      =>  'allergen',
				'title'         =>  'Peanuts',
				'description'	=> 'Peanut allergy is one of the most common and dangerous food allergies, frequently leading to severe reactions, including anaphylaxis, which requires immediate medical attention.',
			),
			'nuts'        => array(
				'category'      =>  'allergen',
				'title'         =>  'Nuts',
				'description'	=> 'Tree nuts, such as almonds, walnuts, and cashews, are among the most serious food allergens, often causing severe reactions, including anaphylaxis.',
			),
			'sesame'      => array(
				'category'      =>  'allergen',
				'title'         =>  'Sesame',
				'description'	=> 'A sesame allergy is an immune system reaction to proteins in sesame seeds or sesame oil, leading to symptoms like hives, swelling, difficulty breathing, or anaphylaxis in severe cases. It can trigger mild to life-threatening responses, similar to other common food allergies like peanut or tree nut allergies.',
			),
			'lupin'       => array(
				'category'      =>  'allergen',
				'title'         =>  'Lupin',
				'description'	=> 'Lupin is a legume that is sometimes used in flour or baked goods. People with lupin allergies may experience symptoms ranging from mild digestive discomfort to severe anaphylactic reactions.',
			),
			'soya'        => array(
				'category'      =>  'allergen',
				'title'         =>  'Soya',
				'description'	=> 'Soy allergy is common in children and can cause reactions such as digestive issues, skin reactions, or, in severe cases, anaphylaxis. Soy is found in many processed foods.',
			),
			'mustard'     => array(
				'category'      =>  'allergen',
				'title'         =>  'Mustard',
				'description'	=> 'Mustard allergy is common in Europe and can cause reactions such as skin irritation, respiratory symptoms, or anaphylaxis. Mustard is often found in sauces, dressings, and spices.',
			),
			'eggs'        => array(
				'category'      =>  'allergen',
				'title'         =>  'Eggs',
				'description'	=> 'Eggs are a frequent allergen, particularly in young children. Symptoms of egg allergies can include skin reactions, respiratory issues, or gastrointestinal problems.',
			),
			'dairy'       => array(
				'category'      =>  'allergen',
				'title'         =>  'Dairy',
				'description'	=> 'Dairy allergies are common, especially in children, and can cause reactions like skin rashes, digestive issues, or anaphylaxis. It involves a reaction to proteins found in cow’s milk.',
			),
			'fish'        => array(
				'category'      =>  'allergen',
				'title'         =>  'Fish',
				'description'	=> 'Fish allergy can cause severe reactions such as hives, swelling, or anaphylaxis. Unlike shellfish, fish allergies often include species like salmon, tuna, and cod.',
			),
			'crustaceans' => array(
				'category'      =>  'allergen',
				'title'         =>  'Crustaceans',
				'description'	=> 'Crustaceans such as shrimp, lobster, and crab are among the most common food allergens. This allergy can be life-threatening and often leads to reactions like swelling, breathing difficulties, or anaphylaxis.',
			),
			'molluscs'    => array(
				'category'      =>  'allergen',
				'title'         =>  'Molluscs',
				'description'	=> 'Molluscs include clams, mussels, oysters, and squid. Mollusc allergies can lead to reactions similar to crustacean allergies, such as hives, swelling, or difficulty breathing.',
			),
			'gluten'      => array(
				'category'      =>  'allergen',
				'title'         =>  'Gluten',
				'description'	=> 'Gluten is a protein found in wheat, barley, and rye. For people with celiac disease or gluten sensitivity, consuming gluten can lead to digestive issues, skin problems, or other serious health complications.',
			),
			'corn'        => array(
				'category'      =>  'allergen',
				'title'         =>  'Corn',
				'description'	=> 'Corn and corn-based products, such as corn starch and corn oil, can trigger allergic reactions. While less common, corn allergies can cause symptoms like digestive issues or respiratory problems.',
			),
			'wheat'       => array(
				'category'      =>  'allergen',
				'title'         =>  'Wheat',
				'description'	=> 'Wheat allergy is common in children and causes reactions such as hives, gastrointestinal distress, or anaphylaxis. It is different from gluten sensitivity, which specifically involves the gluten protein found in wheat.',
			),
			'celery'      => array(
				'category'      =>  'allergen',
				'title'         =>  'Celery',
				'description'	=> 'Celery is commonly used in soups, broths, and spice mixes. People with a celery allergy may experience severe reactions, ranging from skin rashes to breathing difficulties.',
			),
			'sulfite'     => array(
				'category'      =>  'allergen',
				'title'         =>  'Sulfite',
				'description'	=> 'Sulfites are preservatives used in foods and beverages like wine, dried fruits, and pickled products. Sulfite sensitivity can cause asthma-like symptoms and, in rare cases, severe allergic reactions.',
			),
			'alcohol'     => array(
				'category'      =>  'allergen',
				'title'         =>  'Alcohol',
				'description'	=> 'Lupin is a legume that is sometimes used in flour or baked goods. People with lupin allergies may experience symptoms ranging from mild digestive discomfort to severe anaphylactic reactions.',
			),
			'vegetarian'  => array(
				'category'      =>  'dietary',
				'title'         =>  'Vegetarian',
				'description'	=> 'Een dieet dat vlees en vis uitsluit, maar vaak wel zuivelproducten en eieren toelaat, afhankelijk van het type vegetariër.',
			),
			'vegan'       => array(
				'category'      =>  'dietary',
				'title'         =>  'Vegan',
				'description'	=> 'Een dieet waarbij alle dierlijke producten worden vermeden, inclusief vlees, zuivel, eieren, honing en alle producten van dierlijke oorsprong.',
			),
			'halal'       => array(
				'category'      =>  'dietary',
				'title'         =>  'Halal',
				'description'	=> 'Voedsel dat volgens islamitische voorschriften is bereid, waarbij bijvoorbeeld varkensvlees en alcohol verboden zijn, en dieren ritueel worden geslacht.',
			),
			'pregnant'    => array(
				'category'      =>  'dietary',
				'title'         =>  'Risk for pregnant women',
				'description'	=> 'Bepaalde voedingsmiddelen, zoals rauw vlees, vis, ongepasteuriseerde zuivel, en cafeïne, kunnen schadelijk zijn voor de gezondheid van zwangere vrouwen en hun baby.',
			),);

		private const OPTIONS2 = array(
			'peanuts'     => array(
				'path'          => 'allergens-dietary-ictoria/assets/icons/allergens_peanuts.png',
				'name'          => 'allergens_peanuts.png'
			),
			'nuts'        => array(
				'path'          => 'allergens-dietary-ictoria/assets/icons/allergens_nuts.png',
				'name'          => 'allergens_nuts.png'
			),
			'sesame'      => array(
				'path'          => 'allergens-dietary-ictoria/assets/icons/allergens_sesame.png',
				'name'          => 'allergens_sesame.png'
			),
			'lupin'       => array(
				'path'          => 'allergens-dietary-ictoria/assets/icons/allergens_lupin.png',
				'name'          => 'allergens_lupin.png'
			),
			'soya'        => array(
				'path'          => 'allergens-dietary-ictoria/assets/icons/allergens_soya.png',
				'name'          => 'allergens_soya.png'
			),
			'mustard'     => array(
				'path'          => 'allergens-dietary-ictoria/assets/icons/allergens_mustard.png',
				'name'          => 'allergens_mustard.png'
			),
			'eggs'        => array(
				'path'          => 'allergens-dietary-ictoria/assets/icons/allergens_eggs.png',
				'name'          => 'allergens_eggs.png'
			),
			'dairy'       => array(
				'path'          => 'allergens-dietary-ictoria/assets/icons/allergens_dairy.png',
				'name'          => 'allergens_dairy.png'
			),
			'fish'        => array(
				'path'          => 'allergens-dietary-ictoria/assets/icons/allergens_fish.png',
				'name'          => 'allergens_fish.png'
			),
			'crustaceans' => array(
				'path'          => 'allergens-dietary-ictoria/assets/icons/allergens_crustaceans.png',
				'name'          => 'allergens_crustaceans.png'
			),
			'molluscs'    => array(
				'path'          => 'allergens-dietary-ictoria/assets/icons/allergens_molluscs.png',
				'name'          => 'allergens_molluscs.png'
			),
			'gluten'      => array(
				'path'          => 'allergens-dietary-ictoria/assets/icons/allergens_gluten.png',
				'name'          => 'allergens_gluten.png'
			),
			'corn'        => array(
				'path'          => 'allergens-dietary-ictoria/assets/icons/allergens_corn.png',
				'name'          => 'allergens_corn.png'
			),
			'wheat'       => array(
				'path'          => 'allergens-dietary-ictoria/assets/icons/allergens_wheat.png',
				'name'          => 'allergens_wheat.png'
			),
			'celery'      => array(
				'path'          => 'allergens-dietary-ictoria/assets/icons/allergens_celery.png',
				'name'          => 'allergens_celery.png'
			),
			'sulfite'     => array(
				'path'          => 'allergens-dietary-ictoria/assets/icons/allergens_sulfite.png',
				'name'          => 'allergens_sulfite.png'
			),
			'alcohol'     => array(
				'path'          => 'allergens-dietary-ictoria/assets/icons/allergens_alcohol.png',
				'name'          => 'allergens_alcohol.png'
			),
			'vegetarian'  => array(
				'path'          => 'allergens-dietary-ictoria/assets/icons/dietary_vegetarian.png',
				'name'          => 'dietary_vegetarian.png'
			),
			'vegan'       => array(
				'path'          => 'allergens-dietary-ictoria/assets/icons/dietary_vegan.png',
				'name'          => 'dietary_vegan.png'
			),
			'halal'       => array(
				'path'          => 'allergens-dietary-ictoria/assets/icons/dietary_halal.png',
				'name'          => 'dietary_halal.png'
			),
			'pregnant'    => array(
				'path'          => 'allergens-dietary-ictoria/assets/icons/dietary_pregnant.png',
				'name'          => 'dietary_pregnant.png'
			),);

			

		    private const OPTIONS3 = array(
				'peanuts'     => array(
					'name'  => 'allergens_peanuts.png',
					'title' => 'Peanuts',
				),
				'nuts'        => array(
					'name'  => 'allergens_nuts.png',
					'title' => 'Nuts',
				),
				'sesame'      => array(
					'name'  => 'allergens_sesame.png',
					'title' => 'Sesame',
				),
				'lupin'       => array(
					'name'  => 'allergens_lupin.png',
					'title' => 'Lupin',
				),
				'soya'        => array(
					'name'  => 'allergens_soya.png',
					'title' => 'Soya',
				),
				'mustard'     => array(
					'name'  => 'allergens_mustard.png',
					'title' => 'Mustard',
				),
				'eggs'        => array(
					'name'  => 'allergens_eggs.png',
					'title' => 'Eggs',
				),
				'dairy'       => array(
					'name'  => 'allergens_dairy.png',
					'title' => 'Dairy',
				),
				'fish'        => array(
					'name'  => 'allergens_fish.png',
					'title' => 'Fish',
				),
				'crustaceans' => array(
					'name'  => 'allergens_crustaceans.png',
					'title' => 'Crustaceans',
				),
				'molluscs'    => array(
					'name'  => 'allergens_molluscs.png',
					'title' => 'Molluscs',
				),
				'gluten'      => array(
					'name'  => 'allergens_gluten.png',
					'title' => 'Gluten',
				),
				'corn'        => array(
					'name'  => 'allergens_corn.png',
					'title' => 'Corn',
				),
				'wheat'       => array(
					'name'  => 'allergens_wheat.png',
					'title' => 'Wheat',
				),
				'celery'      => array(
					'name'  => 'allergens_celery.png',
					'title' => 'Celery',
				),
				'sulfite'     => array(
					'name'  => 'allergens_sulfite.png',
					'title' => 'Sulfite',
				),
				'alcohol'     => array(
					'name'  => 'allergens_alcohol.png',
					'title' => 'Alcohol',
				),
				'vegetarian'  => array(
					'name'  => 'dietary_vegetarian.png',
					'title' => 'Vegetarian',
				),
				'vegan'       => array(
					'name'  => 'dietary_vegan.png',
					'title' => 'Vegan',
				),
				'halal'       => array(
					'name'  => 'dietary_halal.png',
					'title' => 'Halal',
				),
				'pregnant'    => array(
					'name'  => 'dietary_pregnant.png',
					'title' => 'Risk for pregnant women',
				)
			);

			

			public static function getOptions1() {
				return self::OPTIONS1;
			}
		
			public static function getOptions2() {
				return self::OPTIONS2;
			}
		
			public static function getOptions3() {
				return self::OPTIONS3;
			}
			

	public static function insert_standard_data(){
		if ( ! class_exists( 'Allergens_Dietary_Ictoria_Allergen_Queries' ) ) {
			require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/DB/allergen.php';
		}
			//DB includes
			global $wpdb;
			$table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy';
			$allergy_name = 'Nuts';  // Ensure this is correctly defined

			$sql = $wpdb->prepare(
    			"SELECT COUNT(*) FROM $table_name WHERE allergy_name = %s",
    			$allergy_name
			);

			$exists = $wpdb->get_var( $sql );
	
			if ( $exists > 0 ) {
				// Record exists!
			} else {
				// Record does not exist
				Allergens_Dietary_Ictoria_Allergen_Queries::includeItems();
			}

	}

		

	}
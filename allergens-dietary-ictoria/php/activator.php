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

	public static function default_allergens() {
		// Every option has a category, title, status, filter-action, filter-extra and icon
		// status is used to enable/disable an option globally
		// filter-action is used to tell the filter if products with the selected option should be included or excluded
		// filter-extra is used to add extra text in front of the option in the filter menu
		$no      = __( 'no ', 'allergens-dietary-ictoria' );
		$options = array(
			'peanuts'     => array(
				'category'      => __( 'allergen', 'allergens-dietary-ictoria' ),
				'title'         => __( 'Peanuts', 'allergens-dietary-ictoria' ),
				'description'	=> 'Peanut allergy is one of the most common and dangerous food allergies, frequently leading to severe reactions, including anaphylaxis, which requires immediate medical attention.',
				'path'          => 'allergens-dietary-ictoria/assets/icons/allergens_peanuts.png',
				'name'          => 'allergens_peanuts.png'
			),
			'nuts'        => array(
				'category'      => __( 'allergen', 'allergens-dietary-ictoria' ),
				'title'         => __( 'Nuts', 'allergens-dietary-ictoria' ),
				'description'	=> 'Tree nuts, such as almonds, walnuts, and cashews, are among the most serious food allergens, often causing severe reactions, including anaphylaxis.',
				'path'          => 'allergens-dietary-ictoria/assets/icons/allergens_nuts.png',
				'name'          => 'allergens_nuts.png'
			),
			'sesame'      => array(
				'category'      => __( 'allergen', 'allergens-dietary-ictoria' ),
				'title'         => __( 'Sesame', 'allergens-dietary-ictoria' ),
				'description'	=> 'A sesame allergy is an immune reaction to pro',
				'path'          => 'allergens-dietary-ictoria/assets/icons/allergens_sesame.png',
				'name'          => 'allergens_sesame.png'
			),
			'lupin'       => array(
				'category'      => __( 'allergen', 'allergens-dietary-ictoria' ),
				'title'         => __( 'Lupin', 'allergens-dietary-ictoria' ),
				'description'	=> 'Lupin is a legume that is sometimes used in flour or baked goods. People with lupin allergies may experience symptoms ranging from mild digestive discomfort to severe anaphylactic reactions.',
				'path'          => 'allergens-dietary-ictoria/assets/icons/allergens_lupin.png',
				'name'          => 'allergens_lupin.png'
			),
			'soya'        => array(
				'category'      => __( 'allergen', 'allergens-dietary-ictoria' ),
				'title'         => __( 'Soya', 'allergens-dietary-ictoria' ),
				'description'	=> 'Soy allergy is common in children and can cause reactions such as digestive issues, skin reactions, or, in severe cases, anaphylaxis. Soy is found in many processed foods.',
				'path'          => 'allergens-dietary-ictoria/assets/icons/allergens_soya.png',
				'name'          => 'allergens_soya.png'
			),
			'mustard'     => array(
				'category'      => __( 'allergen', 'allergens-dietary-ictoria' ),
				'title'         => __( 'Mustard', 'allergens-dietary-ictoria' ),
				'description'	=> 'Mustard allergy is common in Europe and can cause reactions such as skin irritation, respiratory symptoms, or anaphylaxis. Mustard is often found in sauces, dressings, and spices.',
				'path'          => 'allergens-dietary-ictoria/assets/icons/allergens_mustard.png',
				'name'          => 'allergens_mustard.png'
			),
			'eggs'        => array(
				'category'      => __( 'allergen', 'allergens-dietary-ictoria' ),
				'title'         => __( 'Eggs', 'allergens-dietary-ictoria' ),
				'description'	=> 'Eggs are a frequent allergen, particularly in young children. Symptoms of egg allergies can include skin reactions, respiratory issues, or gastrointestinal problems.',
				'path'          => 'allergens-dietary-ictoria/assets/icons/allergens_eggs.png',
				'name'          => 'allergens_eggs.png'
			),
			'dairy'       => array(
				'category'      => __( 'allergen', 'allergens-dietary-ictoria' ),
				'title'         => __( 'Dairy', 'allergens-dietary-ictoria' ),
				'description'	=> 'Dairy allergies are common, especially in children, and can cause reactions like skin rashes, digestive issues, or anaphylaxis. It involves a reaction to proteins found in cow’s milk.',
				'path'          => 'allergens-dietary-ictoria/assets/icons/allergens_dairy.png',
				'name'          => 'allergens_dairy.png'
			),
			'fish'        => array(
				'category'      => __( 'allergen', 'allergens-dietary-ictoria' ),
				'title'         => __( 'Fish', 'allergens-dietary-ictoria' ),
				'description'	=> 'Fish allergy can cause severe reactions such as hives, swelling, or anaphylaxis. Unlike shellfish, fish allergies often include species like salmon, tuna, and cod.',
				'path'          => 'allergens-dietary-ictoria/assets/icons/allergens_fish.png',
				'name'          => 'allergens_fish.png'
			),
			'crustaceans' => array(
				'category'      => __( 'allergen', 'allergens-dietary-ictoria' ),
				'title'         => __( 'Crustaceans', 'allergens-dietary-ictoria' ),
				'description'	=> 'Crustaceans such as shrimp, lobster, and crab are among the most common food allergens. This allergy can be life-threatening and often leads to reactions like swelling, breathing difficulties, or anaphylaxis.',
				'path'          => 'allergens-dietary-ictoria/assets/icons/allergens_crustaceans.png',
				'name'          => 'allergens_crustaceans.png'
			),
			'molluscs'    => array(
				'category'      => __( 'allergen', 'allergens-dietary-ictoria' ),
				'title'         => __( 'Molluscs', 'allergens-dietary-ictoria' ),
				'description'	=> 'Molluscs include clams, mussels, oysters, and squid. Mollusc allergies can lead to reactions similar to crustacean allergies, such as hives, swelling, or difficulty breathing.',
				'path'          => 'allergens-dietary-ictoria/assets/icons/allergens_molluscs.png',
				'name'          => 'allergens_molluscs.png'
			),
			'gluten'      => array(
				'category'      => __( 'allergen', 'allergens-dietary-ictoria' ),
				'title'         => __( 'Gluten', 'allergens-dietary-ictoria' ),
				'description'	=> 'Gluten is a protein found in wheat, barley, and rye. For people with celiac disease or gluten sensitivity, consuming gluten can lead to digestive issues, skin problems, or other serious health complications.',
				'path'          => 'allergens-dietary-ictoria/assets/icons/allergens_gluten.png',
				'name'          => 'allergens_gluten.png'
			),
			'corn'        => array(
				'category'      => __( 'allergen', 'allergens-dietary-ictoria' ),
				'title'         => __( 'Corn', 'allergens-dietary-ictoria' ),
				'description'	=> 'Corn and corn-based products, such as corn starch and corn oil, can trigger allergic reactions. While less common, corn allergies can cause symptoms like digestive issues or respiratory problems.',
				'path'          => 'allergens-dietary-ictoria/assets/icons/allergens_corn.png',
				'name'          => 'allergens_corn.png'
			),
			'wheat'       => array(
				'category'      => __( 'allergen', 'allergens-dietary-ictoria' ),
				'title'         => __( 'Wheat', 'allergens-dietary-ictoria' ),
				'description'	=> 'Wheat allergy is common in children and causes reactions such as hives, gastrointestinal distress, or anaphylaxis. It is different from gluten sensitivity, which specifically involves the gluten protein found in wheat.',
				'path'          => 'allergens-dietary-ictoria/assets/icons/allergens_wheat.png',
				'name'          => 'allergens_wheat.png'
			),
			'celery'      => array(
				'category'      => __( 'allergen', 'allergens-dietary-ictoria' ),
				'title'         => __( 'Celery', 'allergens-dietary-ictoria' ),
				'description'	=> 'Celery is commonly used in soups, broths, and spice mixes. People with a celery allergy may experience severe reactions, ranging from skin rashes to breathing difficulties.',
				'path'          => 'allergens-dietary-ictoria/assets/icons/allergens_celery.png',
				'name'          => 'allergens_celery.png'
			),
			'sulfite'     => array(
				'category'      => __( 'allergen', 'allergens-dietary-ictoria' ),
				'title'         => __( 'Sulfite', 'allergens-dietary-ictoria' ),
				'description'	=> 'Sulfites are preservatives used in foods and beverages like wine, dried fruits, and pickled products. Sulfite sensitivity can cause asthma-like symptoms and, in rare cases, severe allergic reactions.',
				'path'          => 'allergens-dietary-ictoria/assets/icons/allergens_sulfite.png',
				'name'          => 'allergens_sulfite.png'
			),
			'alcohol'     => array(
				'category'      => __( 'allergen', 'allergens-dietary-ictoria' ),
				'title'         => __( 'Alcohol', 'allergens-dietary-ictoria' ),
				'description'	=> 'Lupin is a legume that is sometimes used in flour or baked goods. People with lupin allergies may experience symptoms ranging from mild digestive discomfort to severe anaphylactic reactions.',
				'path'          => 'allergens-dietary-ictoria/assets/icons/allergens_alcohol.png',
				'name'          => 'allergens_alcohol.png'
			),
			'vegetarian'  => array(
				'category'      => __( 'dietary', 'allergens-dietary-ictoria' ),
				'title'         => __( 'Vegetarian', 'allergens-dietary-ictoria' ),
				'description'	=> 'Een dieet dat vlees en vis uitsluit, maar vaak wel zuivelproducten en eieren toelaat, afhankelijk van het type vegetariër.',
				'path'          => 'allergens-dietary-ictoria/assets/icons/dietary_vegetarian.png',
				'name'          => 'dietary_vegetarian.png'
			),
			'vegan'       => array(
				'category'      => __( 'dietary', 'allergens-dietary-ictoria' ),
				'title'         => __( 'Vegan', 'allergens-dietary-ictoria' ),
				'description'	=> 'Een dieet waarbij alle dierlijke producten worden vermeden, inclusief vlees, zuivel, eieren, honing en alle producten van dierlijke oorsprong.',
				'path'          => 'allergens-dietary-ictoria/assets/icons/dietary_vegan.png',
				'name'          => 'dietary_vegan.png'
			),
			'halal'       => array(
				'category'      => __( 'dietary', 'allergens-dietary-ictoria' ),
				'title'         => __( 'Halal', 'allergens-dietary-ictoria' ),
				'description'	=> 'Voedsel dat volgens islamitische voorschriften is bereid, waarbij bijvoorbeeld varkensvlees en alcohol verboden zijn, en dieren ritueel worden geslacht.',
				'path'          => 'allergens-dietary-ictoria/assets/icons/dietary_halal.png',
				'name'          => 'dietary_halal.png'
			),
			'pregnant'    => array(
				'category'      => __( 'dietary', 'allergens-dietary-ictoria' ),
				'title'         => __( 'Risk for pregnant women', 'allergens-dietary-ictoria' ),
				'description'	=> 'Bepaalde voedingsmiddelen, zoals rauw vlees, vis, ongepasteuriseerde zuivel, en cafeïne, kunnen schadelijk zijn voor de gezondheid van zwangere vrouwen en hun baby.',
				'path'          => 'allergens-dietary-ictoria/assets/icons/dietary_pregnant.png',
				'name'          => 'dietary_pregnant.png'
			),);

        return $options;
    }

	private static function insert_standard_data(){
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

			if ( isset( $wpdb ) && $wpdb instanceof wpdb ) {
				echo 'Database is loaded and ready' . $exists . '';
			} else {
				echo 'Database is not loaded.';
			}
	
			if ( $exists > 0 ) {
				// Record exists!
			} else {
				// Record does not exist
				Allergens_Dietary_Ictoria_Allergen_Queries::includeItems();
			}
	}
	
}

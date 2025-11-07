## EN: Anomaly Class Bar Block

### EN: Description

This WordPress plugin provides a Gutenberg block for displaying an "Anomaly Class Bar". The block is rendered server-side and includes customizable attributes for containment, disruption, risk, and clearance levels. The plugin also supports per-block image overrides and admin-defined mappings for containment, disruption, and risk classes.

### EN: Features

- **Server-side rendering**: Ensures consistent output and compatibility with themes.
- **Customizable attributes**: Includes containment, disruption, risk, and clearance levels.
- **Admin-defined mappings**: Allows administrators to associate images and labels with specific containment, disruption, and risk classes.
- **Per-block image overrides**: Supports custom images for containment, disruption, and risk classes.
- **Dark mode compatibility**: Neutralizes unwanted dark mode transformations.

### EN: Installation

1. Download the plugin and place it in your WordPress `wp-content/plugins` directory.

2. Activate the plugin through the WordPress admin dashboard.

3. Configure image mappings in the "Anom Bar" settings page.

### EN: Usage

#### EN: Adding the Block

1. In the WordPress editor, search for "Anomaly Class Bar" in the block library.

2. Add the block to your post or page.

3. Customize the attributes in the block settings panel:

   - **Item**: Specify the anomaly item number.

   - **Containment**: Select the containment class.

   - **Disruption**: Select the disruption class.

   - **Risk**: Select the risk class.

   - **Clearance**: Automatically determined based on the level number.

#### EN: Admin Settings

1. Navigate to the "Anom Bar" settings page in the WordPress admin dashboard.

2. Configure image mappings for containment, disruption, and risk classes.

3. Save your changes.

### EN: Development

#### EN: File Structure

- `blocks/anomaly/block.json`: Defines block attributes and metadata.

- `blocks/anomaly/editor.js`: Handles block editing in the Gutenberg editor.

- `blocks/anomaly/style.css`: Contains styles for the block.

- `wp-anom-bar.php`: Main plugin file, handles server-side rendering and admin settings.

#### EN: Customization

- Modify `block.json` to add or remove attributes.

- Update `editor.js` to customize the block editor interface.

- Edit `style.css` to change the appearance of the block.

- Extend `wp-anom-bar.php` for additional server-side functionality.

### EN: License

This plugin is licensed under the `CC BY-SA 3.0` License.

### EN: Support

For issues or feature requests, please contact the plugin author or submit a request through the WordPress support forum.

## FR: Barre de Classe d'Anomalie

### FR: Description

Ce plugin WordPress fournit un bloc Gutenberg pour afficher une "Barre de Classe d'Anomalie". Le bloc est rendu côté serveur et inclut des attributs personnalisables pour le confinement, la perturbation, le risque et les niveaux d'autorisation. Le plugin prend également en charge les remplacements d'images par bloc et les mappages définis par l'administrateur pour les classes de confinement, de perturbation et de risque.

### FR: Fonctionnalités

- **Rendu côté serveur** : Assure une sortie cohérente et une compatibilité avec les thèmes.
- **Attributs personnalisables** : Inclut le confinement, la perturbation, le risque et les niveaux d'autorisation.
- **Mappages définis par l'administrateur** : Permet aux administrateurs d'associer des images et des étiquettes à des classes spécifiques de confinement, de perturbation et de risque.
- **Remplacements d'images par bloc** : Prend en charge des images personnalisées pour les classes de confinement, de perturbation et de risque.
- **Compatibilité avec le mode sombre** : Neutralise les transformations indésirables du mode sombre.

### FR: Installation

1. Téléchargez le plugin et placez-le dans le répertoire `wp-content/plugins` de votre WordPress.

2. Activez le plugin via le tableau de bord d'administration de WordPress.

3. Configurez les mappages d'images dans la page des paramètres "Anom Bar".

### FR: Utilisation

#### FR: Ajouter le Bloc

1. Dans l'éditeur WordPress, recherchez "Barre de Classe d'Anomalie" dans la bibliothèque de blocs.

2. Ajoutez le bloc à votre publication ou page.

3. Personnalisez les attributs dans le panneau des paramètres du bloc :

   - **Élément** : Spécifiez le numéro de l'élément d'anomalie.

   - **Confinement** : Sélectionnez la classe de confinement.

   - **Perturbation** : Sélectionnez la classe de perturbation.

   - **Risque** : Sélectionnez la classe de risque.

   - **Autorisation** : Déterminée automatiquement en fonction du numéro de niveau.

#### FR: Paramètres Administrateur

1. Accédez à la page des paramètres "Anom Bar" dans le tableau de bord d'administration de WordPress.

2. Configurez les mappages d'images pour les classes de confinement, de perturbation et de risque.

3. Enregistrez vos modifications.

### FR: Développement

#### FR: Structure des Fichiers

- `blocks/anomaly/block.json` : Définit les attributs et les métadonnées du bloc.

- `blocks/anomaly/editor.js` : Gère l'édition du bloc dans l'éditeur Gutenberg.

- `blocks/anomaly/style.css` : Contient les styles pour le bloc.

- `wp-anom-bar.php` : Fichier principal du plugin, gère le rendu côté serveur et les paramètres administratifs.

#### FR: Personnalisation

- Modifiez `block.json` pour ajouter ou supprimer des attributs.

- Mettez à jour `editor.js` pour personnaliser l'interface de l'éditeur de blocs.

- Modifiez `style.css` pour changer l'apparence du bloc.

- Étendez `wp-anom-bar.php` pour des fonctionnalités supplémentaires côté serveur.

### FR: Licence

Ce plugin est sous licence `CC BY-SA 3.0`.

### FR: Support

Pour des problèmes ou des demandes de fonctionnalités, veuillez contacter l'auteur du plugin ou soumettre une demande via le forum de support WordPress.

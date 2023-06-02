<p align="center">
    <a href="" rel="noopener">
        <img width="700px" height="700px" src="https://github.com/OUGC-Network/OUGC-Disable-Quotes-Media/assets/1786584/56487894-7247-46f2-be33-a9dc68d9baed" alt="Project logo">
    </a>
</p>

<h3 align="center">OUGC Disable Quotes Media</h3>

<div align="center">

[![Status](https://img.shields.io/badge/status-active-success.svg)]()
[![GitHub Issues](https://img.shields.io/github/issues/OUGC-Network/OUGC-Disable-Quotes-Media.svg)](./issues)
[![GitHub Pull Requests](https://img.shields.io/github/issues-pr/OUGC-Network/OUGC-Disable-Quotes-Media.svg)](./pulls)
[![License](https://img.shields.io/badge/license-GPL-blue)](/LICENSE)

</div>

---

<p align="center"> Convert image and video embeds inside quotes to links.
    <br> 
</p>

## 📜 Table of Contents <a name = "table_of_contents"></a>

- [About](#about)
- [Getting Started](#getting_started)
	- [Dependencies](#dependencies)
	- [File Structure](#file_structure)
	- [Install](#install)
	- [Update](#update)
- [Templates](#templates)
- [Usage](#usage)
- [Built Using](#built_using)
- [Authors](#authors)
- [Acknowledgments](#acknowledgement)
- [Support & Feedback](#support)

## 🚀 About <a name = "about"></a>

OUGC Disable Quotes Media effortlessly enhances readability by converting image and video embeds within quoted content into convenient links, preventing clutter and improving the overall aesthetic of your forum. Say goodbye to messy threads and hello world to a more organized and visually appealing discussion platform with clutter-free posts.

[Go up to Table of Contents](#table_of_contents)

## 📍 Getting Started <a name = "getting_started"></a>

The following information will assist you into getting a copy of this plugin up and running on your forum.

### Dependencies <a name = "dependencies"></a>

A setup that meets the following requirements is necessary to use this plugin.

- [MyBB](https://mybb.com/) >= 1.8.30
- PHP >= 7.4
- [MyBB-PluginLibrary](https://github.com/frostschutz/MyBB-PluginLibrary) >= 13

### File structure <a name = "file_structure"></a>

  ```
   .
   ├── inc
   │ ├── plugins
   └────── ougc_disablequotemedia.php
   ```

### Installing <a name = "install"></a>

Follow the next steps in order to install a copy of this plugin on your forum.

1. Download the latest package from the [MyBB Extend](https://community.mybb.com/mods.php?action=view&pid=1397) site or from the [repository releases](https://github.com/OUGC-Network/OUGC-Disable-Quotes-Media/releases/latest).
2. Upload the contents of the _Upload_ folder to your MyBB root directory.
3. Browse to _Configuration » Plugins_ and install this plugin by clicking _Install & Activate_.

### Updating <a name = "update"></a>

Follow the next steps in order to update your copy of this plugin.

1. Browse to _Configuration » Plugins_ and deactivate this plugin by clicking _Deactivate_.
2. Follow step 1 and 2 from the [Install](#install) section.
3. Browse to _Configuration » Plugins_ and activate this plugin by clicking _Activate_.

[Go up to Table of Contents](#table_of_contents)

## 📐 Templates <a name = "templates"></a>

The following is a list of templates available for this plugin.

- `ougcdisablequotemedia`
	- _front end_; used when parsing simple quotes.  The [core equivalent](https://github.com/mybb/mybb/blob/bc03b9be63cf2f1c31b672f16d64552e4f0736d7/inc/class_parser.php#L875) for this is hard-coded.

[Go up to Table of Contents](#table_of_contents)

## 📖 Usage <a name="usage"></a>

This plugin has no setting or configuration; installing and activating should be sufficient to get this plugin working.

[Go up to Table of Contents](#table_of_contents)

## ⛏ Built Using <a name = "built_using"></a>

- [MyBB](https://mybb.com/) - Web Framework
- [MyBB PluginLibrary](https://github.com/frostschutz/MyBB-PluginLibrary) - A collection of useful functions for MyBB
- [PHP](https://www.php.net/) - Server Environment

[Go up to Table of Contents](#table_of_contents)

## ✍️ Authors <a name = "authors"></a>

- [@Omar G](https://github.com/Sama34) - Idea & Initial work

See also the list of [contributors](https://github.com/OUGC-Network/OUGC-Disable-Quotes-Media/contributors) who participated in this project.

[Go up to Table of Contents](#table_of_contents)

## 🎉 Acknowledgements <a name = "acknowledgement"></a>

- [The Documentation Compendium](https://github.com/kylelobo/The-Documentation-Compendium)

[Go up to Table of Contents](#table_of_contents)

## 🎈 Support & Feedback <a name="support"></a>

This is free development and any contribution is welcome. Get support or leave feedback at the official [MyBB Community](https://community.mybb.com/thread-229080.html).

Thanks for downloading and using our plugins!

[Go up to Table of Contents](#table_of_contents)
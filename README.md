# Link Preview #

Version: 1.2

## Offers a field that will place a link to the frontend in the publish pages

### SPECS ###

Using the brackets syntax, it permits to create frontend links related to the entry.

### REQUIREMENTS ###

- Symphony CMS version 2.3.4 and up (as of the day of the last release of this extension)

### INSTALLATION ###

- `git clone` / download and unpack the tarball file
- Put into the extension directory
- Enable/install just like any other extension

See <http://getsymphony.com/learn/tasks/view/install-an-extension/>

*Voila !*

Come say hi! -> <http://www.deuxhuithuit.com/>

### HOW TO USE ###

- Add a Link Preview field to your section.
- Set up the url format using values from other fields.
- Use "Anchor Label" to give your link a custom label.
- A simple link will be added next to the title of the entry.

### HOW TO SET UP THE URL FORMAT ###

- Use {$fieldname} to include the handle of a field
- Use {$fieldname:value} to include the value instead of the handle
- Use {$system:id} to include the id of an entry
- Use qualifiers for php date_format to format date and datetime fields:
	- i.e. /article/{$date:Y}/{$date:m}/{$date:d}/{$title}/
- Use qualifiers for php date_format to format the current system date:
	- i.e. /today/{$system:date:Y}/{$system:date:m}/{$system:date:d}/


### LICENSE ###

MIT <http://deuxhuithuit.mit-license.org>

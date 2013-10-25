# Link Preview #

Version: 1.0

## Offers a field that will place a link to the frontend in the publish pages ##

### SPECS ###

Usign the brackets syntax, it permits to create frontend links related to the entry.

### REQUIREMENTS ###

- Symphony CMS version 2.3.4 and up (as of the day of the last release of this extension)
- You also need this pull request <https://github.com/symphonycms/symphony-2/pull/1836>

### INSTALLATION ###

- `git clone` / download and unpack the tarball file
- Put into the extension directory
- Enable/install just like any other extension

See <http://getsymphony.com/learn/tasks/view/install-an-extension/>

*Voila !*

Come say hi! -> <http://www.deuxhuithuit.com/>

### HOW TO USE ###

- Add a Link Preview field to your section.
- Set up the url format usign values from other fields.
- A simple link will be added next to the title of the entry.
	- You can format date and datetime fields usign qualifier for php date_format.
	i.e. /article/{$date:Y}/{$date:m}/{$date:d}/{$title}/

### LICENSE ###

MIT <http://deuxhuithuit.mit-license.org>

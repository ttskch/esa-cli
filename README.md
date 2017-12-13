# esa-cli

## Requirements

- PHP 7.1.3+

## Installation

```bash
$ composer create-project ttskch/esa-cli:@dev
$ cd esa-cli
$ cp app/parameters.php{.placeholder,}
$ vi app/parameters.php   # tailor to you env
$ ln -s $(pwd)/app/esa /usr/local/bin/esa
```

## Usage

### grep

```bash
$ esa grep -s 'on:category/subcategory #tag1' -e '^#' -l
category/subcategory/post1:1:# foo
category/subcategory/post1:3:## bar
category/subcategory/post1:5:### baz
category/subcategory/post2:1:# foo
category/subcategory/post2:3:## bar

$ esa grep -h   # see help
```

Learn about queries for `-s` at https://docs.esa.io/posts/104

### sed

// todo

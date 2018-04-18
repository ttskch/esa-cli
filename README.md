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

### diff

Just print diff url for the post because [esa doesn't provide Revision API yet](https://docs.esa.io/posts/102#%E4%BB%8A%E5%BE%8C%E3%81%AE%E5%AE%9F%E8%A3%85%E4%BA%88%E5%AE%9A).

```bash
$ esa diff <post_id> <base_revision_number>
https://team_name.esa.io/posts/<post_id>/revisions/compare/<compare_revision_number>...<base_revision_number>/diff

$ esa diff -h   # see help
```

### sed

// todo

worldcubeassociation.org [![Build Status](https://travis-ci.org/cubing/worldcubeassociation.org.svg?branch=master)](https://travis-ci.org/cubing/worldcubeassociation.org)
========================

This repository contains all of the code that runs on [worldcubeassociation.org](https://www.worldcubeassociation.org/).

## Setup
- Install [Vagrant](https://www.vagrantup.com/), which requires
  [VirtualBox](https://www.virtualbox.org/).
- `git clone https://github.com/cubing/worldcubeassociation.org` - Clone this repo! (And navigate into it, `cd worldcubeassociation.org`)
- `git submodule update --init --recursive` - Initialize submodules.
- `(cd WcaOnRails; bundle install) && pre-commit install` - Set up git pre-commit hook. Optional, but very useful.

## Run in Vagrant (easier and gets everything working)
- `vagrant up noregs` - Once the VM finishes initializing (which can take some time),
  the website will be accessible at [http://localhost:2331](http://localhost:2331).
  - Note: Starting up the `noregs` vm is much faster than the `all` vm, because the dependencies required to build the WCA regulations take *ages* to install.
  - Note: There are some minor [issues with development on Windows](https://github.com/cubing/worldcubeassociation.org/issues/393).
- All emails will be accessible at `http://localhost:2332`.
- Please take a look at this [wiki page](https://github.com/cubing/worldcubeassociation.org/wiki/Misc.-important-commands-to-know) for more detailed informations about the application's internals.

## Run locally ruby (lightweight, but only run the rails portions of the site)
- We don't support development with sqlite3, you'll need to set up MySQL.
- [Mailcatcher](http://mailcatcher.me/) is a good tool for catching emails in development.

## Provision New VM
- Provisioning relies upon SSH agent forwarding, so make sure you've set up an SSH
  key for cubing@worldcubeassociation.org in order to rsync secrets.
- `time ssh -A user@example.com 'sudo wget https://raw.githubusercontent.com/cubing/worldcubeassociation.org/master/scripts/wca-bootstrap.sh -O /tmp/wca-bootstrap.sh && sudo -E bash /tmp/wca-bootstrap.sh staging/production'`

## Deploy
- `git fetch upstream`
- `git checkout upstream/master`
- `git push upstream HEAD:production`
- `ssh -A cubing@worldcubeassociation.org worldcubeassociation.org/scripts/deploy.sh pull_latest rebuild_regs rebuild_rails`
  - NOTE: This command needs to be run twice in the event `deploy.sh` changes. See https://github.com/cubing/worldcubeassociation.org/issues/651.
  (`rebuild_regs` can be skipped most of the time)
- Enter the password

## Secrets
- Production secrets are stored in an encrypted chef [data bag](https://docs.chef.io/data_bags.html) at `chef/data_bags/secrets/production.json`.
  - Show secrets: `knife data bag show secrets production -c /etc/chef/solo.rb --secret-file secrets/my_secret_key`
  - Edit secrets: `knife data bag edit secrets production -c /etc/chef/solo.rb --secret-file secrets/my_secret_key`

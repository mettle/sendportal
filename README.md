<p align="center"><img src="https://sendportal.io/img/sendportal.png" width="250"></p>


Modern open-source self-hosted email marketing.

- [Website](https://sendportal.io)
- [Documentation](https://sendportal.io/docs)

## Introduction

The core functionality of SendPortal is contained within the [SendPortal Core](https://github.com/mettle/sendportal-core) package. If you would like to add SendPortal to an existing application that already handles user authentication, you only require [SendPortal Core](https://github.com/mettle/sendportal-core).

## Features
SendPortal includes subscriber and list management, email campaigns, message tracking, reports and multiple workspaces/domains in a modern, flexible and scalable application.

SendPortal integrates with [Amazon SES](https://aws.amazon.com/ses), [Postmark](https://postmarkapp.com), [Sendgrid](https://sendgrid.com), [Mailgun](https://www.mailgun.com/) and [Mailjet](https://www.mailjet.com).

The [SendPortal](https://github.com/mettle/sendportal) application acts as a wrapper around SendPortal Core. This will allow you to run your own copy of SendPortal as a stand-alone application, including user authentication and multiple workspaces.

## Installation

If you would like to install SendPortal as a stand-alone application, please follow the [installation guide](https://sendportal.io/docs/v2/getting-started/installation).

If you would like to add SendPortal to an existing application, please follow the [package installation guide](https://sendportal.io/docs/v2/getting-started/package-installation).

## Requirements
SendPortal V3 requires:

- PHP 8.2+
- Laravel 10+
- MySQL (≥ 5.7) or PostgreSQL (≥ 9.4)

If you are on an earlier version of PHP (7.3+) or Laravel (8+), please use [SendPortal V2](https://github.com/mettle/sendportal/releases/tag/v2.0.4)

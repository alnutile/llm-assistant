## LLM Assistant

![](/docs/images/rocket.jpg)

### About

"LLMAssistant" or "LLMass.io" for now (Still working on the name 😄).

This tool is like your personal assistant, helping you stay organized, market your services, and even create content. 
Imagine a tool that collects interesting articles for you, gives you a TL;DR, and even helps you write job posting replies.
It's powered by OpenAI, which means it can perform various functions like scheduling tasks and summarizing your day. 
It's like having a bullet journal that writes itself.
Stay tuned for more updates on this game-changer. 
And hey, I'm all ears for any feedback or suggestions you might have!

### Feature Ideas

  * Threaded topics so you can work through ideas with the LLM 
  * Email URL to get article for later
  * Email any content with tags so that it will save and tag
  * Email attachments for TLDRs and storage
  * Scheduled Emails to send you daily summary
  * Help you write freelance replies to gigs and connect to various APIs
  * Build schedules and keep you on track
  * Run functions build into the system like sending emails, get content from links and more.
  * Integrate with image generation tools so you can easily create and iterate
  * Integration into other systems to help you automate your day-to-day workflows

### Install and Setup

This is a just a normal Laravel Application. It will need Sail just for the Postgres embedding feature.


### Schedule Email box checker

Just update the `.env` and make sure you have scheduling setup for every minute and send away

If the email has a url it will get the results

```dotenv
IMAP_HOST=mail.foobar.com
IMAP_PORT=993
IMAP_ENCRYPTION=ssl
IMAP_VALIDATE_CERT=true
IMAP_USERNAME=user@foo.bar
IMAP_PASSWORD=password
IMAP_DEFAULT_ACCOUNT=default
IMAP_PROTOCOL=imap

```

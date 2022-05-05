# Blog Configuration

Add the following lines to a .yml config file of your choice:
~~~~
SilverStripe\Blog\Model\Blog:
  extensions:
    - CyberDuck\SEO\Model\Extension\SeoBlogExtension
SilverStripe\Blog\Model\BlogPost:
  extensions:
    - CyberDuck\SEO\Model\Extension\SeoBlogPostExtension
~~~~

## Default Blog Post Meta

After installation the Blog Page type will have a new tab called Post SEO in the CMS with the following configuration.

  - Default Meta title - Use page Title when no Meta title set for Blog Post
  - Default Meta description - Use page summary when no Meta description set for Blog Post
  - Use featured image as social image - Uses the featured image in social sharing Meta
  

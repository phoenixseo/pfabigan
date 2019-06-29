<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* themes/custom/thunder_cat/templates/html.html.twig */
class __TwigTemplate_3c8ed1e84b239ca41a2c28af5e6d24e6945cca3473d6509a0f804fab69fe6a23 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = [];
        $filters = ["escape" => 29, "safe_join" => 32, "t" => 64];
        $functions = [];

        try {
            $this->sandbox->checkSecurity(
                [],
                ['escape', 'safe_join', 't'],
                []
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->getSourceContext());

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 28
        echo "<!DOCTYPE html>
<html";
        // line 29
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["html_attributes"] ?? null)), "html", null, true);
        echo ">
  <head>
    <head-placeholder token=\"";
        // line 31
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["placeholder_token"] ?? null)), "html", null, true);
        echo "\">
    <title>";
        // line 32
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar($this->env->getExtension('Drupal\Core\Template\TwigExtension')->safeJoin($this->env, $this->sandbox->ensureToStringAllowed(($context["head_title"] ?? null)), " | "));
        echo "</title>
    <css-placeholder token=\"";
        // line 33
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["placeholder_token"] ?? null)), "html", null, true);
        echo "\">
    <js-placeholder token=\"";
        // line 34
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["placeholder_token"] ?? null)), "html", null, true);
        echo "\">
    <!-- Google Tag Manager -->
\t<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
\tnew Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
\tj=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
\t'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
\t})(window,document,'script','dataLayer','GTM-W25N6KG');</script>
\t<!-- End Google Tag Manager -->
  <!-- favicon stuff -->
<link rel=\"apple-touch-icon\" sizes=\"180x180\" href=\"/apple-touch-icon.png?v=eEG88GAvE9\">
<link rel=\"icon\" type=\"image/png\" sizes=\"32x32\" href=\"/favicon-32x32.png?v=eEG88GAvE9\">
<link rel=\"icon\" type=\"image/png\" sizes=\"16x16\" href=\"/favicon-16x16.png?v=eEG88GAvE9\">
<link rel=\"manifest\" href=\"/site.webmanifest?v=eEG88GAvE9\">
<link rel=\"mask-icon\" href=\"/safari-pinned-tab.svg?v=eEG88GAvE9\" color=\"#5bbad5\">
<link rel=\"shortcut icon\" href=\"/favicon.ico?v=eEG88GAvE9\">
<meta name=\"apple-mobile-web-app-title\" content=\"zax-wop.de\">
<meta name=\"application-name\" content=\"zax-wop.de\">
<meta name=\"msapplication-TileColor\" content=\"#ffc40d\">
<meta name=\"theme-color\" content=\"#ffffff\">
  </head>
  <body";
        // line 54
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["attributes"] ?? null)), "html", null, true);
        echo ">
  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src=\"https://www.googletagmanager.com/ns.html?id=GTM-W25N6KG\"
  height=\"0\" width=\"0\" style=\"display:none;visibility:hidden\"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->
    ";
        // line 63
        echo "    <a href=\"#main-content\" class=\"visually-hidden focusable\">
      ";
        // line 64
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar(t("Skip to main content"));
        echo "
    </a>
    ";
        // line 66
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["page_top"] ?? null)), "html", null, true);
        echo "
    ";
        // line 67
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["page"] ?? null)), "html", null, true);
        echo "
    ";
        // line 68
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["page_bottom"] ?? null)), "html", null, true);
        echo "
    <js-bottom-placeholder token=\"";
        // line 69
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["placeholder_token"] ?? null)), "html", null, true);
        echo "\">
  </body>
</html>
";
    }

    public function getTemplateName()
    {
        return "themes/custom/thunder_cat/templates/html.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  126 => 69,  122 => 68,  118 => 67,  114 => 66,  109 => 64,  106 => 63,  98 => 54,  75 => 34,  71 => 33,  67 => 32,  63 => 31,  58 => 29,  55 => 28,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "themes/custom/thunder_cat/templates/html.html.twig", "/mnt/webroot/sites/zax-wop/web/themes/custom/thunder_cat/templates/html.html.twig");
    }
}

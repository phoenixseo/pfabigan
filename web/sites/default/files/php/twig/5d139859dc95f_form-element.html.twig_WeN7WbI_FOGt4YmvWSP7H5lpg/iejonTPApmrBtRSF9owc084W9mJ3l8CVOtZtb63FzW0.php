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

/* themes/contrib/thunder_admin/templates/form/form-element.html.twig */
class __TwigTemplate_c6d8417ff7f05d034c1ed5665eb6a1f65eec98388192946c7d520e0c74d7efab extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = ["set" => 49, "if" => 71, "include" => 93];
        $filters = ["escape" => 47, "clean_class" => 52];
        $functions = ["attach_library" => 47];

        try {
            $this->sandbox->checkSecurity(
                ['set', 'if', 'include'],
                ['escape', 'clean_class'],
                ['attach_library']
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
        // line 47
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->env->getExtension('Drupal\Core\Template\TwigExtension')->attachLibrary("thunder_admin/form-toggle-description"), "html", null, true);
        echo "
";
        // line 49
        $context["classes"] = [0 => "js-form-item", 1 => "form-item", 2 => ("js-form-type-" . \Drupal\Component\Utility\Html::getClass($this->sandbox->ensureToStringAllowed(        // line 52
($context["type"] ?? null)))), 3 => ("form-type-" . \Drupal\Component\Utility\Html::getClass($this->sandbox->ensureToStringAllowed(        // line 53
($context["type"] ?? null)))), 4 => ("js-form-item-" . \Drupal\Component\Utility\Html::getClass($this->sandbox->ensureToStringAllowed(        // line 54
($context["name"] ?? null)))), 5 => ("form-item-" . \Drupal\Component\Utility\Html::getClass($this->sandbox->ensureToStringAllowed(        // line 55
($context["name"] ?? null)))), 6 => ((!twig_in_filter(        // line 56
($context["title_display"] ?? null), [0 => "after", 1 => "before"])) ? ("form-no-label") : ("")), 7 => (((        // line 57
($context["disabled"] ?? null) == "disabled")) ? ("form-disabled") : ("")), 8 => ((        // line 58
($context["errors"] ?? null)) ? ("form-item--error") : (""))];
        // line 62
        $context["description_classes"] = [0 => "description", 1 => "form-item__description", 2 => "js-form-item__description", 3 => "js-description", 4 => (((        // line 67
($context["description_display"] ?? null) == "invisible")) ? ("visually-hidden") : (""))];
        // line 70
        echo "<div";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["attributes"] ?? null), "addClass", [0 => ($context["classes"] ?? null)], "method")), "html", null, true);
        echo " data-form-description-container>
  ";
        // line 71
        if (twig_in_filter(($context["label_display"] ?? null), [0 => "before", 1 => "invisible"])) {
            // line 72
            echo "    ";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["label"] ?? null)), "html", null, true);
            echo "
  ";
        }
        // line 74
        echo "
  ";
        // line 75
        if (((($context["description_display"] ?? null) == "before") && $this->getAttribute(($context["description"] ?? null), "content", []))) {
            // line 76
            echo "    <div";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute($this->getAttribute(($context["description"] ?? null), "attributes", []), "addClass", [0 => ($context["description_classes"] ?? null)], "method"), "addClass", [0 => "form-item__description--before"], "method")), "html", null, true);
            echo ">
      ";
            // line 77
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["description"] ?? null), "content", [])), "html", null, true);
            echo "
    </div>
  ";
        }
        // line 80
        echo "
  <div class=\"form-item__field-wrapper";
        // line 81
        if ($this->getAttribute(($context["description"] ?? null), "content", [])) {
            echo " form-item__field-wrapper--has-description";
        }
        echo "\">
    ";
        // line 82
        if ( !twig_test_empty(($context["prefix"] ?? null))) {
            // line 83
            echo "      <span class=\"field-prefix\">";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["prefix"] ?? null)), "html", null, true);
            echo "</span>
    ";
        }
        // line 85
        echo "    ";
        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["children"] ?? null)), "html", null, true);
        echo "
    ";
        // line 86
        if ( !twig_test_empty(($context["suffix"] ?? null))) {
            // line 87
            echo "      <span class=\"field-suffix\">";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["suffix"] ?? null)), "html", null, true);
            echo "</span>
    ";
        }
        // line 89
        echo "    ";
        if ((($context["label_display"] ?? null) == "after")) {
            // line 90
            echo "      ";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["label"] ?? null)), "html", null, true);
            echo "
    ";
        }
        // line 92
        echo "    ";
        if ($this->getAttribute(($context["description"] ?? null), "content", [])) {
            // line 93
            echo "      ";
            $this->loadTemplate("@thunder_admin/button-description-toggle.html.twig", "themes/contrib/thunder_admin/templates/form/form-element.html.twig", 93)->display(twig_array_merge($context, ["class" => "form-item__toggle-description"]));
            // line 94
            echo "    ";
        } else {
            // line 95
            echo "      <div class=\"form-item__toggle-description-placeholder\">&nbsp;</div>
    ";
        }
        // line 97
        echo "  </div>

  ";
        // line 99
        if (($context["errors"] ?? null)) {
            // line 100
            echo "    <div class=\"form-item--error-message\">
      <strong>";
            // line 101
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["errors"] ?? null)), "html", null, true);
            echo "</strong>
    </div>
  ";
        }
        // line 104
        echo "  ";
        if ((twig_in_filter(($context["description_display"] ?? null), [0 => "after", 1 => "invisible"]) && $this->getAttribute(($context["description"] ?? null), "content", []))) {
            // line 105
            echo "    <div";
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($this->getAttribute($this->getAttribute(($context["description"] ?? null), "attributes", []), "addClass", [0 => ($context["description_classes"] ?? null)], "method"), "addClass", [0 => "form-item__description--after"], "method")), "html", null, true);
            echo ">
      ";
            // line 106
            echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute(($context["description"] ?? null), "content", [])), "html", null, true);
            echo "
    </div>
  ";
        }
        // line 109
        echo "</div>
";
    }

    public function getTemplateName()
    {
        return "themes/contrib/thunder_admin/templates/form/form-element.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  181 => 109,  175 => 106,  170 => 105,  167 => 104,  161 => 101,  158 => 100,  156 => 99,  152 => 97,  148 => 95,  145 => 94,  142 => 93,  139 => 92,  133 => 90,  130 => 89,  124 => 87,  122 => 86,  117 => 85,  111 => 83,  109 => 82,  103 => 81,  100 => 80,  94 => 77,  89 => 76,  87 => 75,  84 => 74,  78 => 72,  76 => 71,  71 => 70,  69 => 67,  68 => 62,  66 => 58,  65 => 57,  64 => 56,  63 => 55,  62 => 54,  61 => 53,  60 => 52,  59 => 49,  55 => 47,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "themes/contrib/thunder_admin/templates/form/form-element.html.twig", "/mnt/webroot/sites/zax-wop/web/themes/contrib/thunder_admin/templates/form/form-element.html.twig");
    }
}

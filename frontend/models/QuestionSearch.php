<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Question;

/**
 * QuestionSearch represents the model behind the search form about `common\models\Question`.
 */
class QuestionSearch extends Question
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'id',
                    'view_count',
                    'answer_count',
                    'follow_count',
                    'updated_by',
                    'updated_at',
                    'created_by',
                    'created_at',
                    'is_deleted'
                ],
                'integer'
            ],
            [['title', 'detail', 'status'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Question::find();
        if (isset($params['sort']) && $params['sort'] == 'unanswered') {
            $query = $query->andWhere(['answer_count' => 0]);
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort'       => [
                'defaultOrder' => [
                    'active' => SORT_ASC,
                ],
                'attributes'   => [
                    'active'     => [
                        'asc'     => ['updated_at' => SORT_DESC],
                        'desc'    => ['updated_at' => SORT_DESC],
                        'default' => SORT_ASC,
                        'label'   => '最新的',
                    ],
                    'hottest'    => [
                        'asc'     => [
                            'answer_count' => SORT_DESC,
                            'follow_count' => SORT_DESC,
                            'view_count'   => SORT_DESC
                        ],
                        'desc'    => [
                            'answer_count' => SORT_DESC,
                            'follow_count' => SORT_DESC,
                            'view_count'   => SORT_DESC
                        ],
                        'default' => SORT_ASC,
                        'label'   => '热门的',
                    ],
                    'latest'     => [
                        'asc'     => ['created_at' => SORT_DESC, 'updated_at' => SORT_DESC],
                        'desc'    => ['created_at' => SORT_DESC, 'updated_at' => SORT_DESC],
                        'default' => SORT_ASC,
                        'label'   => '最新的',
                    ],
                    'unanswered' => [
                        'asc'     => [
                            'answer_count' => SORT_ASC,
                            'follow_count' => SORT_DESC,
                            'view_count'   => SORT_DESC
                        ],
                        'desc'    => [
                            'answer_count' => SORT_ASC,
                            'follow_count' => SORT_DESC,
                            'view_count'   => SORT_DESC
                        ],
                        'default' => SORT_ASC,
                        'label'   => '未回答',
                    ],
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id'           => $this->id,
            'view_count'   => $this->view_count,
            'answer_count' => $this->answer_count,
            'follow_count' => $this->follow_count,
            'updated_by'   => $this->updated_by,
            'updated_at'   => $this->updated_at,
            'created_by'   => $this->created_by,
            'created_at'   => $this->created_at,
            'is_deleted'   => $this->is_deleted,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'detail', $this->detail])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
